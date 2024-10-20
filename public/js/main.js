document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const toggle = document.querySelector('.toggle'); 
    const cameraStatus = document.querySelector('.camera-status'); 
    let stream; 

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(mediaStream) {
                stream = mediaStream; 
                video.srcObject = stream; 
                cameraStatus.style.display = 'none'; 
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
                this.textContent = 'Stop Camera'; 
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
            this.textContent = 'Start Camera'; 
        }
    });
});
