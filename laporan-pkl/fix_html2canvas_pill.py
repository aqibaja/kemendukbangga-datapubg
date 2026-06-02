import re

with open('resources/views/laporan-capaian.blade.php', 'r') as f:
    content = f.read()

# The regex should match:
# <div class="relative flex items-center justify-center text-center font-bold [FONT_CLASSES] text-teal-900 bg-yellow-400 pl-16 sm:pl-20 pr-6 py-2 rounded-full shadow-[0_5px_15px_rgba(255,215,0,0.4)] uppercase border-2 border-white">
#     <div class="absolute -left-4 sm:-left-6 top-1/2 -translate-y-1/2 w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden">
#         <img src="..." class="..." alt="...">
#     </div>
#     <span class="pill-text inline-block relative z-10" style="top: 0px;">TITLE</span>
# </div>

pattern = re.compile(
    r'<div class="relative flex items-center justify-center (text-center font-bold ([^"]*?) text-teal-900 bg-yellow-400) pl-16 sm:pl-20 (pr-6 py-2 rounded-full shadow-\[0_5px_15px_rgba\(255,215,0,0\.4\)\] uppercase border-2 border-white)">\s*'
    r'<div class="absolute -left-4 sm:-left-6 top-1/2 -translate-y-1/2 w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden">\s*'
    r'(<img [^>]+>)\s*'
    r'</div>\s*'
    r'<span class="pill-text inline-block relative z-10" style="top: 0px;">([^<]+)</span>\s*'
    r'</div>'
)

def replacer(match):
    font_classes = match.group(1) # e.g. text-center font-bold text-sm sm:text-xl text-teal-900 bg-yellow-400
    suffix_classes = match.group(3)
    img_tag = match.group(4)
    title = match.group(5)
    
    return f"""<div class="relative inline-flex items-center">
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden">
                                    {img_tag}
                                </div>
                                <div class="{font_classes} pl-12 sm:pl-16 {suffix_classes} ml-4 sm:ml-6 relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">{title}</span>
                                </div>
                            </div>"""

new_content = pattern.sub(replacer, content)

# Check for the ones without text-center (like some might not have it)
pattern2 = re.compile(
    r'<div class="relative flex items-center justify-center (font-bold ([^"]*?) text-teal-900 bg-yellow-400) pl-16 sm:pl-20 (pr-6 py-2 rounded-full shadow-\[0_5px_15px_rgba\(255,215,0,0\.4\)\] uppercase border-2 border-white)">\s*'
    r'<div class="absolute -left-4 sm:-left-6 top-1/2 -translate-y-1/2 w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden">\s*'
    r'(<img [^>]+>)\s*'
    r'</div>\s*'
    r'<span class="pill-text inline-block relative z-10" style="top: 0px;">([^<]+)</span>\s*'
    r'</div>'
)

def replacer2(match):
    font_classes = match.group(1)
    suffix_classes = match.group(3)
    img_tag = match.group(4)
    title = match.group(5)
    
    return f"""<div class="relative inline-flex items-center">
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden">
                                    {img_tag}
                                </div>
                                <div class="text-center {font_classes} pl-12 sm:pl-16 {suffix_classes} ml-4 sm:ml-6 relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">{title}</span>
                                </div>
                            </div>"""

new_content = pattern2.sub(replacer2, new_content)

with open('resources/views/laporan-capaian.blade.php', 'w') as f:
    f.write(new_content)

print(f"Replaced {len(pattern.findall(content)) + len(pattern2.findall(content))} instances.")

