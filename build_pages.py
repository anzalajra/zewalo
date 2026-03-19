import os
import re

framework_dir = r"d:\6. Kints\Projects\Zewalo\pageframework"
views_dir = r"d:\6. Kints\Projects\Zewalo\resources\views\landing"
features_dir = os.path.join(views_dir, "features")
os.makedirs(features_dir, exist_ok=True)

# 1. Update header.blade.php
with open(os.path.join(framework_dir, "header-megamenu.html"), "r", encoding="utf-8") as f:
    header_html = f.read()

header_match = re.search(r'(<header.*?</header>)', header_html, re.DOTALL)
if header_match:
    header_content = header_match.group(1)
    # prepend missing styles
    header_content = """<style>
        .mega-menu {
            display: none;
        }
        .group:hover .mega-menu {
            display: block;
        }
    </style>
""" + header_content

    # Fix auth buttons
    header_content = re.sub(
        r'<button([^>]*?)>\s*Login\s*</button>',
        r'<a href="/login-tenant" \1>Login</a>',
        header_content
    )
    header_content = re.sub(
        r'<button([^>]*?)>\s*Get Started\s*</button>',
        r'<a href="/register-tenant" \1>Get Started</a>',
        header_content
    )

    # Replace mega menu links
    # "Live Inventory Stock" -> {{ route('landing.features.live-stock') }}
    header_content = re.sub(
        r'<a([^>]+?)href="#"[^>]*?(>.*?Live Inventory Stock.*?)</a>',
        r"<a\1href=\"{{ url('/feature/live-stock') }}\"\2</a>",
        header_content, flags=re.DOTALL
    )
    # Advanced Management -> inventory-management
    header_content = re.sub(
        r'<a([^>]+?)href="#"[^>]*?(>.*?Advanced Management.*?)</a>',
        r"<a\1href=\"{{ url('/feature/inventory-management') }}\"\2</a>",
        header_content, flags=re.DOTALL
    )
    # Booking Online
    header_content = re.sub(
        r'<a([^>]+?)href="#"[^>]*?(>.*?Booking Online.*?)</a>',
        r"<a\1href=\"{{ url('/feature/booking-online') }}\"\2</a>",
        header_content, flags=re.DOTALL
    )
    # Quotation & Invoicing
    header_content = re.sub(
        r'<a([^>]+?)href="#"[^>]*?(>.*?Quotation &amp; Invoicing.*?)</a>',
        r"<a\1href=\"{{ url('/feature/quotation-invoicing') }}\"\2</a>",
        header_content, flags=re.DOTALL
    )
    # Rental & Financial Reports
    header_content = re.sub(
        r'<a([^>]+?)href="#"[^>]*?(>.*?Rental &amp; Financial Reports.*?)</a>',
        r"<a\1href=\"{{ url('/feature/reporting') }}\"\2</a>",
        header_content, flags=re.DOTALL
    )
    
    # Generic links "Solutions", "Pricing", "Resources", etc.
    header_content = header_content.replace('href="#"', 'href="{{ url(\'/\') }}"')

    with open(os.path.join(views_dir, "partials", "header.blade.php"), "w", encoding="utf-8") as f:
        f.write(header_content)

# 2. Process all other HTML files to blade files
files_map = {
    "page-feature.html": "feature.blade.php",
    "page-contact.html": "contact.blade.php",
    "page-careers.html": "careers.blade.php",
    "page-aboutus.html": "about-us.blade.php",
    "childfeature-bookingonline.html": "features/booking-online.blade.php",
    "childfeature-inventorymanagement.html": "features/inventory-management.blade.php",
    "childfeature-livestock.html": "features/live-stock.blade.php",
    "childfeature-quoinv.html": "features/quotation-invoicing.blade.php",
    "childfeature-reporting.html": "features/reporting.blade.php",
}

for html_file, blade_file in files_map.items():
    with open(os.path.join(framework_dir, html_file), "r", encoding="utf-8") as f:
        content = f.read()
    
    # Check if there is a main tag
    # inject header after `<div class="relative ... group/design-root...">` 
    # Because if we inject it right after body, it might be outside the layout-wrapper
    # Let's see if there is `<div class="relative ... flex-col overflow-x-hidden">`
    # or just right after `<body ...>`
    content = re.sub(r'(<body[^>]*>)', r"\1\n    <!-- BEGIN: MainHeader -->\n    @include('landing.partials.header')\n    <!-- END: MainHeader -->\n", content)
    
    content = re.sub(r'(</body>)', r"    <!-- BEGIN: MainFooter -->\n    @include('landing.partials.footer')\n    <!-- END: MainFooter -->\n\1", content)
    
    out_path = os.path.join(views_dir, blade_file)
    with open(out_path, "w", encoding="utf-8") as f:
        f.write(content)

print("Done")
