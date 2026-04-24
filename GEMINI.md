# Project Memories & Mandates

## 👥 Profiling & Personnel Management
- **Lurah & Kepala Desa Integration**:
    - **Lurah** profiles are mapped to the `lurah` type. Their institution follows the pattern `Kecamatan [Nama Kecamatan]`.
    - **Kepala Desa** profiles are mapped to the `kepala-desa` type. Their position is strictly "Kepala Desa", and their institution strictly contains the village name (e.g., "Sanjai", without the "Desa" prefix).
    - **Data Integrity**: The "Pemerintah" and "Desa " prefixes have been removed from village names for Kepala Desa types.
- **Admin UI Logic**:
    - **Field Order**: The `Tipe` dropdown is now the first field in the profile creation/edit form.
    - **Dynamic Visibility**: 
        - For **Lurah**, geographical inputs (Kecamatan, Kelurahan, Desa) are hidden as they are captured in the Institution/OPD field.
        - For **Kepala Desa**, only the `Kecamatan` field is shown.
    - **Placeholders**: The `Instansi / OPD` field has dynamic placeholders and its label changes to **"Desa"** when the type is set to `Kepala Desa`.
    - **Pagination & Search**: The admin profiles list includes a search bar (name, position, institution) and type-based filtering with 20 items per page.
    - **Sorting Logic**: 
        - Profiles are generally sorted by `type` (ASC), then `order` (ASC), with a fallback to `created_at` (ASC).
        - **Lurah** profiles are specifically sorted by their OPD/Institution (`institution` ASC) before applying the `order` sorting.
        - **Kepala Desa** profiles are specifically sorted by their `kecamatan` (ASC), then by their Desa/Institution (`institution` ASC) before applying the `order` sorting.
    - **Validation Feedback**: Admin forms now use the `needs-validation` Bootstrap pattern for real-time validity checks on mandatory fields like `Jabatan` and `Urutan Tampil`.
- **Frontend Display**:
    - **Sequential Numbering**: Tables in the frontend profile list include a "No" column.
    - **Column Layout**: In the Kepala Desa section, the "Kecamatan" column is moved to the end.

## 🛠 Database & System
- **Profile Slug Generation**: Automatic unique slug generation has been added to the `ProfileService` to ensure profile links are human-readable and to prevent database insertion errors due to missing unique fields.
- **Proxy API**: Internal routes `/admin/profiles/get_kecamatan` and `/admin/profiles/get_wilayah` act as proxies to bypass CORS when fetching geographical data from `apps.sinjaikab.go.id`.
- **Validation**: Image uploads are optional for specific types: `forkopimda`, `eselon-ii`, `eselon-iii`, `lurah`, and `kepala-desa`. Mandatory fields for all profiles include `type`, `position`, and `order`.
- **SQL Export**: A clean SQL dump utility exists to export personnel data without IDs or test records for production synchronization.

## 🎨 Layout Standards
- **Component Inclusion**: Header and footer partials have been merged directly into `app/Views/layouts/frontend.php` to prevent redundant tags and improve render speed as per user preference.
