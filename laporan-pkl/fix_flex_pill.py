import re

with open('resources/views/laporan-capaian.blade.php', 'r') as f:
    content = f.read()

# Pattern for the current structure
pattern = re.compile(
    r'<div class="relative inline-flex items-center">\s*'
    r'<div class="absolute left-0 top-1/2 -translate-y-1/2 w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden">\s*'
    r'(<img [^>]+>)\s*'
    r'</div>\s*'
    r'<div class="([^"]+?) pl-12 sm:pl-16([^"]+?) ml-4 sm:ml-6 relative z-10">\s*'
    r'<span class="pill-text inline-block relative z-10" style="top: 0px;">([^<]+)</span>\s*'
    r'</div>\s*'
    r'</div>'
)

def replacer(match):
    img_tag = match.group(1)
    font_classes = match.group(2).replace('text-center', '').strip() # We can put text-center explicitly
    suffix_classes = match.group(3).strip()
    title = match.group(4)
    
    return f"""<div class="inline-flex items-center">
                                <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-teal-900 rounded-full border-2 sm:border-4 border-yellow-400 flex items-center justify-center shadow-lg z-20 overflow-hidden flex-shrink-0 -mr-6 sm:-mr-10">
                                    {img_tag}
                                </div>
                                <div class="text-center {font_classes} pl-10 sm:pl-14 {suffix_classes} relative z-10">
                                    <span class="pill-text inline-block relative z-10" style="top: 0px;">{title}</span>
                                </div>
                            </div>"""

new_content = pattern.sub(replacer, content)

with open('resources/views/laporan-capaian.blade.php', 'w') as f:
    f.write(new_content)

print(f"Replaced {len(pattern.findall(content))} instances.")

