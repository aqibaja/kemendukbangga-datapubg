from PIL import Image

def process_image(input_path, output_path):
    try:
        img = Image.open(input_path).convert("RGBA")
        datas = img.getdata()
        
        newData = []
        # Target dark green color approx #064e3b (R:6, G:78, B:59)
        # We will make dark green pixels transparent
        for item in datas:
            # Check if pixel is dark green (very low red, medium green, low-mid blue)
            if item[0] < 50 and item[1] < 120 and item[2] < 100 and item[1] > item[0] and item[1] > item[2]:
                newData.append((255, 255, 255, 0)) # transparent
            else:
                newData.append(item)
                
        img.putdata(newData)
        img.save(output_path, "PNG")
        print("Success processing " + input_path)
    except Exception as e:
        print("Failed: " + str(e))

process_image("public/image/catin_icon.png", "public/image/catin_icon_trans.png")
process_image("public/image/bumil_icon.png", "public/image/bumil_icon_trans.png")
