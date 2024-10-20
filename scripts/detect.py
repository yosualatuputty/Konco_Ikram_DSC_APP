import json
import torch
import sys
from ultralytics import YOLO  
import cv2
import os

def detect_video(image_path):
    
    model_path = 'C:/Users/ikram/OneDrive/Dokumen/GitHub/Konco_Ikram_DSC/Folder Website/models/best.pt'
    if not os.path.exists(model_path):
        return

    try:
        model = YOLO(model_path) 
    except Exception as e:
        return
    
    frame = cv2.imread(image_path)
    if frame is None:
        return

    image = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)

    try:
        results = model.predict(source=image)  
        
    except Exception as e:
        return
    
    detections = []
    conf_threshold = 0.2 
    for detection in results[0].boxes:
        x1, y1, x2, y2 = detection.xyxy[0].tolist()  
        conf = detection.conf[0].item()  
        cls_id = detection.cls[0].item()  
    #     print(f"[{x1}, {y1}], [{x2}, {y2}]")
        new = [x1, y1, x2, y2, cls_id, round(conf, 2)]
        with open("result.json", "w") as json_file:
            json.dump(new, json_file)

        # print(f"[{x1}, {y1}, {x2}, {y2}, {cls_id}, {conf:.2f}]")
    
if __name__ == "__main__":
    if len(sys.argv) > 1:
        image_path = sys.argv[1]
        detect_video(image_path)
    