<?php
date_default_timezone_set("Asia/Jakarta");
?>
<script type="text/javascript">
console.log("Manufacturing JS loaded");

document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const toggle = document.querySelector('.toggle');
    const cameraStatus = document.querySelector('.camera-status');
    let stream;
    let drowsyCount = 0; // Counter untuk pelanggaran drowsy
    let frameCountAPD = 0; // Counter untuk frame APD
    let classesDetectedAPD = new Set(); // Set untuk menyimpan kelas yang terdeteksi dalam 100 frame
    let lastViolationTime = ""; // Untuk menyimpan waktu terakhir pelanggaran drowsy
    let drowsyWarn = "";
    let apdWarn = "";
    var div = document.getElementById('desc');
    


    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (mediaStream) {
                stream = mediaStream;
                video.srcObject = stream;
                cameraStatus.style.display = 'none';
                captureFrame();
            })
            .catch(function (error) {
                console.error("Error accessing the camera: ", error);
            });
    }

    toggle.classList.remove('active');
    video.srcObject = null;
    cameraStatus.style.display = 'block';

    toggle.addEventListener('click', function () {
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

            canvas.toBlob(function (blob) {
                sendFrame(blob);
            }, 'image/jpeg');
        }
        setTimeout(captureFrame, 500);
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
                handleDetections(data);
            })
            .catch(error => {
                console.error('Error sending frame to Flask API: ', error);
            });
    }

    function handleDetections(detections) {
        console.log("Detections1: ", detections[0]);
        console.log("Detections2: ", detections[1]);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Detections for drowsiness (detections[0])
        let isDrowsy = false;
        detections[0].forEach(detection => {
            const { label, confidence, bbox } = detection;
            const [x, y, width, height] = bbox;

            ctx.strokeStyle = 'red';
            ctx.lineWidth = 2;
            ctx.strokeRect(x, y, width, height);

            ctx.fillStyle = 'red';
            ctx.font = '16px Arial';
            ctx.fillText(`${label} (${(confidence * 100).toFixed(2)}%)`, x, y > 10 ? y - 5 : 10);

            if (label.toLowerCase() === "drowsy") {
                isDrowsy = true;
            }
        });

        if (isDrowsy) {
            drowsyCount++;
            if (drowsyCount >= 10) {
                lastViolationTime = new Date().toLocaleTimeString(); // Simpan waktu terakhir
                drowsyWarn = `Drowsiness violation detected at ${lastViolationTime}`;
                div.innerHTML = "WARNING DROWSY";
                setTimeout(eraseDiv, 10000);
                console.log(drowsyWarn);
                <?php 
                    $drowsyWarn = date("H:i:s") . "\n";
                    $fp = fopen('drowsy.txt', 'a+');//opens file in append mode  
                    fwrite($fp, $drowsyWarn);  
                    fclose($fp);
                ?>
                // fs.appendFile('../drowsy.txt', drowsyWarn);
                // sendDrowsyViolation(lastViolationTime); // Kirim pelanggaran drowsy ke server
                drowsyCount = 0; // Reset counter setelah pelanggaran terdeteksi
            }
        } else {
            drowsyCount = 0;
        }

        // Detections for APD (detections[1])
        frameCountAPD++;
        detections[1].forEach(detection => {
            const { label, confidence, bbox } = detection;
            const [x, y, width, height] = bbox;

            ctx.strokeStyle = 'blue';
            ctx.lineWidth = 2;
            ctx.strokeRect(x, y, width, height);

            ctx.fillStyle = 'blue';
            ctx.font = '16px Arial';
            ctx.fillText(`${label} (${(confidence * 100).toFixed(2)}%)`, x, y > 10 ? y - 5 : 10);

            classesDetectedAPD.add(label); // Tambahkan kelas yang terdeteksi
        });

        if (frameCountAPD >= 10) {
            checkAPDViolations(); // Periksa pelanggaran APD setelah 100 frame
            frameCountAPD = 0; // Reset counter frame
            classesDetectedAPD.clear(); // Reset set kelas yang terdeteksi
        }
    }

    // function sendDrowsyViolation(time) {
    //     fetch('http://127.0.0.1:5000/drowsy_violation', {
    //         method: 'POST',
    //         // headers: {
    //         //     'Content-Type': 'application/json',
    //         // },
    //         body: JSON.stringify({
    //             violation: 'drowsy',
    //             time: time
    //         })
    //     })
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log('Drowsiness violation sent:', data);
    //             div.innerHTML = "WARNING: DROWSY";
    //             setTimeout(eraseDiv, 3000);
    //         })
    //         .catch(error => {
    //             console.error('Error sending drowsy violation:', error);
    //         });
    // }

    function checkAPDViolations() {
        const requiredClasses = ["Kacamata Pelindung", "Masker"]; // Kelas yang diharapkan ada
        const missingClasses = requiredClasses.filter(cls => !classesDetectedAPD.has(cls)); // Cari kelas yang tidak terdeteksi

        if (missingClasses.length > 0) {
            console.log(`APD violation detected for classes: ${missingClasses.join(', ')}`);
            div.innerHTML += `\nWARNING APD VIOLATION FOR: ${missingClasses.join(', ')}`;
            setTimeout(eraseDiv, 3000);
            <?php 
                    $drowsyWarn = date("H:i:s") . "\n";
                    $fp = fopen('apd.txt', 'a+');//opens file in append mode  
                    fwrite($fp, $drowsyWarn);  
                    fclose($fp);
                ?>
            // sendAPDViolation(missingClasses); // Kirim pelanggaran APD ke server
        }
    }

    // function sendAPDViolation(missingClasses) {
    //     fetch('http://127.0.0.1:5000/apd_violation', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //         },
    //         body: JSON.stringify({
    //             violation: 'apd',
    //             missing_classes: missingClasses,
    //             time: new Date().toLocaleTimeString()
    //         })
    //     })
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log('APD violation sent:', data);
    //         })
    //         .catch(error => {
    //             console.error('Error sending APD violation:', error);
    //         });
    // }
    function eraseDiv(){
        div.innerHTML = "";
    }
});

</script>

