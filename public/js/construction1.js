const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const context = canvas.getContext("2d");
const toggleButton = document.getElementById("toggle-camera");

let streaming = false; 
let intervalId; 
let mediaStream; 

function startCamera() {
    navigator.mediaDevices
        .getUserMedia({ video: true })
        .then((stream) => {
            mediaStream = stream;
            video.srcObject = stream;
            streaming = true;

            video.addEventListener("loadedmetadata", () => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
            });

            intervalId = setInterval(() => {
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                processFrame(); 
            }, 100);
            toggleButton.textContent = "Stop Camera"; 
        })
        .catch((err) => {
            console.error("Error accessing camera: " + err);
        });
}

function stopCamera() {
    clearInterval(intervalId); 
    if (mediaStream) {
        mediaStream.getTracks().forEach((track) => track.stop());
    }
    video.srcObject = null; 
    streaming = false;
    toggleButton.textContent = "Start Camera"; 
}

toggleButton.addEventListener("click", () => {
    if (streaming) {
        stopCamera();
    } else {
        startCamera();
    }
});

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function processFrame() {
    const imageData = canvas.toDataURL("image/png"); 
    if (!imageData) {
        console.error("Data URI tidak valid.");
        return;
    }
    
    fetch("/detect-video", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ video: imageData }), 
    })
    .then((response) => {
        console.log(response)
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then((data) => {
        if (data.result) {
            console.log("Deteksi:", data.result);
            drawResults(imageData, Array.from(data.result)); 
        } else {
            console.log("Tidak ada hasil deteksi.");
        }
    })
    .catch((error) => {
        console.error("Error:", error);
    });
}

function drawResults(imageSrc, detections) {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const img = new Image();
    img.src = imageSrc;
    img.onload = function() {
        context.drawImage(img, 0, 0, canvas.width, canvas.height);

        if (Array.isArray(detections) && detections.length > 0) {
            detections.forEach(detection => {
                const [x1, y1, x2, y2] = detection.box || [0, 0, 0, 0];  
                const label = `${detection.name || 'Unknown'}: ${(detection.conf || 0).toFixed(2)}`; 

                context.strokeStyle = 'green';
                context.lineWidth = 2;
                context.strokeRect(x1, y1, x2 - x1, y2 - y1);

                context.font = '16px Arial';  
                context.fillStyle = 'green'; 
                context.fillText(label, x1, y1 > 20 ? y1 - 10 : y1 + 10); 
            });
        } else {
            console.error("No detections found or 'detections' is not an array.");
        }
    };

    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = ''; 
    resultsDiv.appendChild(canvas); 
}
