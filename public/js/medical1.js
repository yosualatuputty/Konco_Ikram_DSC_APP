console.log("Medical JS loaded");

document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas'); 
    const ctx = canvas.getContext('2d'); 
    const toggle = document.querySelector('.toggle'); 
    const cameraStatus = document.querySelector('.camera-status'); 
    const modelSelect = document.getElementById('modelSelect'); // Ambil elemen dropdown model
    let stream; 

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(mediaStream) {
                stream = mediaStream; 
                video.srcObject = stream; 
                cameraStatus.style.display = 'none'; 
                captureFrame(); 
            })
            .catch(function(error) {
                console.error("Error accessing the camera: ", error);
            });
    }

    toggle.classList.remove('active'); 
    video.srcObject = null; 
    cameraStatus.style.display = 'block'; 

    toggle.addEventListener('click', function() {
        this.classList.toggle('active'); 

        if (this.classList.contains('active')) {
            if (!stream) {
                startCamera(); 
            } else {
                video.srcObject = stream; 
                cameraStatus.style.display = 'none'; 
            }
        } else {
            video.srcObject = null; 
            cameraStatus.style.display = 'block'; 
            if (stream) {
                stream.getTracks().forEach(track => {
                    track.stop(); 
                });
                stream = null; 
            }
        }
    });

    function captureFrame() {
        if (video.srcObject) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(function(blob) {
                sendFrame(blob); 
            }, 'image/jpeg');
        }
        
        setTimeout(captureFrame, 500); 
    }

    function sendFrame(blob) {
        const formData = new FormData();
        formData.append('image', blob);
    
        const selectedModel = modelSelect.value;
        const apiUrl = selectedModel === 'a' ? 'http://127.0.0.1:5001/detect' : 'http://127.0.0.1:5000/detect';
    
        fetch(apiUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            drawDetections(data); 
        })
        .catch(error => {
            console.error('Error sending frame to Flask API: ', error);
        });
    }

    function drawDetections(detections) {
        console.log("Detections: ", detections); 
        ctx.clearRect(0, 0, canvas.width, canvas.height); 
    
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
        detections.forEach(detection => {
            const { label, confidence, bbox } = detection; 
            const [x, y, width, height] = bbox;
    
            ctx.strokeStyle = 'red'; 
            ctx.lineWidth = 2;
            ctx.strokeRect(x, y, width, height);
    
            ctx.fillStyle = 'red'; 
            ctx.font = '16px Arial';
            ctx.fillText(`${label} (${(confidence * 100).toFixed(2)}%)`, x, y > 10 ? y - 5 : 10);
        });
    }
});
