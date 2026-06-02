import re

with open('resources/views/laporan-capaian.blade.php', 'r') as f:
    content = f.read()

# I will extract the blocks for Stock Opname, KB Baru, KB Aktif, mCPR
# and then replace them.

def extract_section(name, content):
    pattern = re.compile(rf'<!-- {name} -->(.*?)<!-- (SECTION \d+|Ornamen Background)', re.DOTALL)
    match = pattern.search(content)
    if match:
        return match.group(1).strip()
    # For mCPR, it goes until the end of the sections
    pattern2 = re.compile(rf'<!-- {name} -->(.*?)</div>\s*</div>\s*</div>\s*@endif\s*@endif', re.DOTALL)
    match2 = pattern2.search(content)
    if match2:
        return match2.group(1).strip()
    return ""

stock = extract_section("SECTION 2: STOCK OPNAME SIRIKA", content)
kb_baru = extract_section("SECTION 3: KB BARU", content)
kb_aktif = extract_section("SECTION 4: KB AKTIF", content)
mcpr = extract_section("SECTION 5: mCPR", content)

# Remove the mt-8 from the root of these sections so they fit nicely in the grid
stock = re.sub(r'^<div class="mt-8 relative">', '<div class="relative h-full flex flex-col">', stock)
kb_baru = re.sub(r'^<div class="mt-8 relative">', '<div class="relative h-full flex flex-col">', kb_baru)
kb_aktif = re.sub(r'^<div class="mt-8 relative">', '<div class="relative h-full flex flex-col">', kb_aktif)
mcpr = re.sub(r'^<div class="mt-8 relative">', '<div class="relative h-full flex flex-col">', mcpr)

# Make grids inside the sections take the remaining height if needed, or adjust inner grids
stock = stock.replace('<div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-2">', '<div class="grid grid-cols-1 gap-4 px-2 flex-grow">')
# KB Baru is md:grid-cols-4, let's make it grid-cols-2
kb_baru = kb_baru.replace('<div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-2">', '<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-2 flex-grow">')
# KB Aktif is md:grid-cols-3, but there's a trick: the second card is a huge one. 
# "grid grid-cols-1 md:grid-cols-3 gap-6 px-2 items-center" -> Let's change to "flex flex-col gap-4 px-2"
kb_aktif = kb_aktif.replace('<div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-2 items-center">', '<div class="flex flex-col gap-4 px-2 flex-grow">')
# mCPR is flex flex-wrap justify-center gap-12
# Let's change gap-12 to gap-6
mcpr = mcpr.replace('gap-12', 'gap-6')

# Construct the new HTML
new_html = f"""
                        <!-- ROW 2: STOCK OPNAME & KB BARU -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 relative z-10">
                            <!-- STOCK OPNAME -->
                            {stock}
                            <!-- KB BARU -->
                            {kb_baru}
                        </div>

                        <!-- ROW 3: KB AKTIF & mCPR -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 relative z-10">
                            <!-- KB AKTIF -->
                            {kb_aktif}
                            <!-- mCPR -->
                            {mcpr}
                        </div>
"""

# Replace the entire block from SECTION 2 to the end
pattern_replace = re.compile(r'<!-- SECTION 2: STOCK OPNAME SIRIKA -->.*?(?=</div>\s*</div>\s*</div>\s*@endif\s*@endif)', re.DOTALL)
content = pattern_replace.sub(new_html, content)

with open('resources/views/laporan-capaian.blade.php', 'w') as f:
    f.write(content)

print("Restructured grid layout")

