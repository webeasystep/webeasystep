from PIL import Image

def process_image():
    input_path = r"d:\laragon\www\fakhrcs\public\site\logo.png"
    
    # Open the image
    img = Image.open(input_path).convert("RGBA")
    
    # Determine bounding box (crop empty space)
    bbox = img.getbbox()
    if bbox:
        img = img.crop(bbox)
        
    sizes = {
        'favicon-16x16.png': (16, 16),
        'favicon-32x32.png': (32, 32),
        'apple-touch-icon.png': (180, 180),
        'android-chrome-192x192.png': (192, 192),
        'android-chrome-512x512.png': (512, 512),
    }
    
    base_dir = r"d:\laragon\www\fakhrcs\public\site"
    
    # Generate favicons
    for filename, size in sizes.items():
        # Create a blank square image with transparent background
        max_dim = max(img.width, img.height)
        square_img = Image.new('RGBA', (max_dim, max_dim), (0, 0, 0, 0))
        # Paste the cropped image in the center
        offset = ((max_dim - img.width) // 2, (max_dim - img.height) // 2)
        square_img.paste(img, offset)
        
        # Resize using LANCZOS
        resized_img = square_img.resize(size, Image.Resampling.LANCZOS)
        resized_img.save(f"{base_dir}\\{filename}", format="PNG")
        
    # Generate favicon.ico
    # We can just use the 256x256 or 64x64 or a combination
    ico_img = Image.new('RGBA', (max_dim, max_dim), (0, 0, 0, 0))
    ico_img.paste(img, offset)
    ico_img.save(f"{base_dir}\\favicon.ico", format="ICO", sizes=[(16,16), (32,32), (48,48), (64,64)])

    # Generate feature_logo.png for og:image (WhatsApp etc.)
    # Size 500x500 with white background as it often renders better in link previews
    og_size = 500
    og_img = Image.new('RGBA', (og_size, og_size), (255, 255, 255, 255))
    
    # We want the logo to take up most of the 500x500 but with some padding
    # Let's target a max dimension of 400 for the logo inside the 500x500 box
    target_dim = 400
    scale = target_dim / max(img.width, img.height)
    new_size = (int(img.width * scale), int(img.height * scale))
    resized_logo = img.resize(new_size, Image.Resampling.LANCZOS)
    
    offset_og = ((og_size - new_size[0]) // 2, (og_size - new_size[1]) // 2)
    
    # Need to paste with alpha mask
    og_img.paste(resized_logo, offset_og, resized_logo)
    # Convert to RGB to ensure no transparency issues on some platforms
    og_img = og_img.convert('RGB')
    og_img.save(f"{base_dir}\\images\\feature_logo.png", format="PNG")
    
    print("All images generated successfully.")

if __name__ == '__main__':
    process_image()
