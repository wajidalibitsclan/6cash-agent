// const basePath = "http://localhost/6cash/public/face/";
// const URL = "http://localhost/6cash";

const basePath = "https://6cash.bitsclan-solutions.com/public/face/";
const URL = "https://6cash.bitsclan-solutions.com";

const video = document.getElementById("videoElement");

let prevEAR = null;
const EAR_THRESHOLD = 0.41;
let blinkCooldown = false;

let counter = 0;
let setIntervalId;

Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri(basePath + "models"),
    faceapi.nets.faceLandmark68Net.loadFromUri(basePath + "models"),
]).then(startVideo);

function startVideo() {
    navigator.mediaDevices
        .getUserMedia({ video: true, audio: false })
        .then((localMediaStream) => {
            video.srcObject = localMediaStream;
            video.addEventListener("loadedmetadata", () => {
                video.play();

                setupCanvas();
            });
        })
        .catch((err) => {
            console.error(`Error accessing camera:`, err);
        });
}

function setupCanvas() {
    const canvas = faceapi.createCanvasFromMedia(video);
    document.querySelector("#canvas").append(canvas);
    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    setIntervalId = setInterval(async () => {
        const detections = await faceapi
            .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks();
        const resizedDetections = faceapi.resizeResults(
            detections,
            displaySize
        );
        canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
        faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);

        if (detections.length > 0) {
            const landmarks = resizedDetections[0].landmarks;
            const leftEye = landmarks.getLeftEye();
            const rightEye = landmarks.getRightEye();

            const leftEAR = calculateEyeAspectRatio(leftEye);
            const rightEAR = calculateEyeAspectRatio(rightEye);

            const avgEAR = (leftEAR + rightEAR) / 2;
            if (prevEAR) {
                console.log(prevEAR);
                if (
                    avgEAR < EAR_THRESHOLD && /// 0.2         0.4.. 0.3 ...
                    prevEAR >= EAR_THRESHOLD &&
                    !blinkCooldown
                ) {
                    console.log("Blink detected!");
                    counter += 30;
                    blinkCooldown = true;
                    setTimeout(() => {
                        blinkCooldown = false;
                    }, 2000);

                    if (counter >= 100) {
                        clearInterval(setIntervalId);
                        document.getElementById("counter-input").value = 100;
                        window.location.href = URL + "/agent/auth/information";
                    } else {
                        document.getElementById("counter-input").value =
                            counter;
                    }
                }
            }

            prevEAR = avgEAR;
        }
    }, 10);
}

function calculateEyeAspectRatio(eye) {
    const verticalDist = euclideanDistance(eye[1], eye[5]);
    const horizontalDist1 = euclideanDistance(eye[0], eye[3]);
    const horizontalDist2 = euclideanDistance(eye[2], eye[4]);
    return (verticalDist / (horizontalDist1 + horizontalDist2)) * 2;
}

function euclideanDistance(point1, point2) {
    return Math.sqrt((point1.x - point2.x) ** 2 + (point1.y - point2.y) ** 2);
}
