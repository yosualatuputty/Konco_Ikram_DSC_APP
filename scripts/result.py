import json

with open("result.json", "r") as json_file:
    new_array = json.load(json_file)

print(new_array)