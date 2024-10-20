<?php
date_default_timezone_set("Asia/Jakarta");
?>
<script type="text/javascript">
    console.log("Manufacturing JS loaded");

    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const toggle = document.querySelector('.toggle');
        const cameraStatus = document.querySelector('.camera-status');
        let stream;
        let drowsyCount = 0;
        let frameCountAPD = 0;
        let classesDetectedAPD = new Set();
        let lastViolationTime = "";
        let drowsyWarn = "";
        let apdWarn = "";
        var div = document.getElementById('desc');



        function startCamera() {
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(mediaStream) {
                    stream = mediaStream;
                    video.srcObject = stream;
                    cameraStatus.style.display = 'none';
                    captureFrame();
                })
                .catch(function(error) {
                    console.error("Error accessing the camera: ", error);
                });
            eraseDiv();
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

            let isDrowsy = false;
            detections[0].forEach(detection => {
                const {
                    label,
                    confidence,
                    bbox
                } = detection;
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
                    lastViolationTime = new Date().toLocaleTimeString();
                    drowsyWarn = `Drowsiness violation detected at ${lastViolationTime}`;
                    var warn = "WARNING DROWSY";
                    div.innerHTML += warn + "<br/>";
                    setTimeout(eraseDiv, 10000);
                    console.log(drowsyWarn);
                    <?php
                    $drowsyWarn = date("H:i:s") . "\n";
                    $fp = fopen('drowsy.txt', 'a+');
                    fwrite($fp, $drowsyWarn);
                    fclose($fp);
                    ?>
                    drowsyCount = 0;
                }
            } else {
                drowsyCount = 0;
            }

            frameCountAPD++;
            detections[1].forEach(detection => {
                const {
                    label,
                    confidence,
                    bbox
                } = detection;
                const [x, y, width, height] = bbox;

                ctx.strokeStyle = 'blue';
                ctx.lineWidth = 2;
                ctx.strokeRect(x, y, width, height);

                ctx.fillStyle = 'blue';
                ctx.font = '16px Arial';
                ctx.fillText(`${label} (${(confidence * 100).toFixed(2)}%)`, x, y > 10 ? y - 5 : 10);

                classesDetectedAPD.add(label);
            });

            if (frameCountAPD >= 10) {
                checkAPDViolations();
                frameCountAPD = 0;
                classesDetectedAPD.clear();
            }
        }



        function checkAPDViolations() {
            const requiredClasses = ["Kacamata Pelindung", "Masker"];
            const missingClasses = requiredClasses.filter(cls => !classesDetectedAPD.has(cls));

            if (missingClasses.length > 0) {
                console.log(`APD violation detected for classes: ${missingClasses.join(', ')}`);
                var warn = `WARNING APD VIOLATION FOR: ${missingClasses.join(', ')}`;
                div.innerHTML += warn + "<br>";
                setTimeout(eraseDiv, 3000);
                <?php
                $drowsyWarn = date("H:i:s") . "\n";
                $fp = fopen('apd.txt', 'a+');
                fwrite($fp, $drowsyWarn);
                fclose($fp);
                ?>
            }
        }

        function eraseDiv() {
            div.innerHTML = "";
        }
    });
</script>