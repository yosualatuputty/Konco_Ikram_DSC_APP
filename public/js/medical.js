console.log("Medical JS loaded");

document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas'); // Tambahkan elemen canvas di HTML
    const ctx = canvas.getContext('2d'); // Inisialisasi konteks canvas
    const toggle = document.querySelector('.toggle'); 
    const cameraStatus = document.querySelector('.camera-status'); 
    let stream; 

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(mediaStream) {
                stream = mediaStream; 
                video.srcObject = stream; 
                cameraStatus.style.display = 'none'; 
                captureFrame(); // Mulai menangkap frame saat kamera aktif
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
            // Set ukuran canvas sama dengan video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Gambar video di canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Ambil gambar dari canvas dan kirim ke Flask API
            canvas.toBlob(function(blob) {
                sendFrame(blob); // Mengirim frame ke Flask API
            }, 'image/jpeg');
        }
        
        // Panggil lagi fungsi ini setelah beberapa saat (untuk real-time capture)
        setTimeout(captureFrame, 500); // Kirim frame setiap 1 detik
    }

    function sendFrame(blob) {
        const formData = new FormData();
        formData.append('image', blob);

        fetch('http://127.0.0.1:5000/detect', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Tangani hasil deteksi di sini
            drawDetections(data); // Panggil fungsi untuk menggambar hasil deteksi
        })
        .catch(error => {
            console.error('Error sending frame to Flask API: ', error);
        });
    }

    function drawDetections(detections) {
        console.log("Detections1: ", detections[0]); // Tambahkan log untuk memeriksa deteksi
        console.log("Detections2: ", detections[1]); // Tambahkan log untuk memeriksa deteksi
        // console.log("Detections2: ", detections.detections2); // Tambahkan log untuk memeriksa deteksi
        ctx.clearRect(0, 0, canvas.width, canvas.height); // Bersihkan canvas sebelum menggambar ulang
    
        // Gambar video di canvas
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
        // Iterasi hasil deteksi dan gambar bounding box
        detections[0].forEach(detection => {
            const { label, confidence, bbox } = detection; // Ambil informasi dari deteksi
            const [x, y, width, height] = bbox;
    
            // Gambar bounding box
            ctx.strokeStyle = 'red'; // Warna garis bounding box
            ctx.lineWidth = 2;
            ctx.strokeRect(x, y, width, height);
    
            // Gambar label
            ctx.fillStyle = 'red'; // Warna teks label
            ctx.font = '16px Arial';
            ctx.fillText(`${label} (${(confidence * 100).toFixed(2)}%)`, x, y > 10 ? y - 5 : 10);
        });

        detections[1].forEach(detection => {
            const { label, confidence, bbox } = detection; // Ambil informasi dari deteksi
            const [x, y, width, height] = bbox;
    
            // Gambar bounding box
            ctx.strokeStyle = 'blue'; // Warna garis bounding box
            ctx.lineWidth = 2;
            ctx.strokeRect(x, y, width, height);
    
            // Gambar label
            ctx.fillStyle = 'blue'; // Warna teks label
            ctx.font = '16px Arial';
            ctx.fillText(`${label} (${(confidence * 100).toFixed(2)}%)`, x, y > 10 ? y - 5 : 10);
        });
    }
});
