# Project Memories & Mandates

## 👥 Profiling & Personnel Management
- **Lurah & Kepala Desa Integration**:
    - **Lurah** profiles are mapped to the `lurah` type. Their position is strictly "Lurah", their institution strictly contains the village/ward name (Kelurahan) and they now explicitly capture `Kecamatan`.
    - **Kepala Desa** profiles are mapped to the `kepala-desa` type. Their position is strictly "Kepala Desa", and their institution strictly contains the village name (e.g., "Sanjai", without the "Desa" prefix).
    - **Data Integrity**: The "Pemerintah" and "Desa "/"Kelurahan " prefixes have been removed from village/ward names for these types.
- **Admin UI Logic**:
    - **Field Order**: The `Tipe` dropdown is now the first field in the profile creation/edit form.
    - **Dynamic Visibility**: 
        - For **Lurah** and **Kepala Desa**, geographical inputs (`Kecamatan` dropdown, `Kelurahan`/`Desa` dropdown acting as the Institution) are shown and dynamically populated from a remote API. `Bio` and `Image` are hidden.
    - **Placeholders**: The `Instansi / OPD` field has dynamic placeholders and its label changes to **"Desa"** or **"Kelurahan"** when the type is set to `Kepala Desa` or `Lurah`, respectively.
    - **Pagination & Search**: The admin profiles list includes a search bar (name, position, institution) and type-based filtering with 20 items per page.
    - **Sorting Logic**: 
        - Profiles are generally sorted by `type` (ASC), then `order` (ASC), with a fallback to `created_at` (ASC).
        - **Lurah** and **Kepala Desa** profiles are specifically sorted by their `kecamatan` (ASC), then by their Kelurahan/Desa (`institution` ASC).
        - **Urutan Tampil** (manual ordering) is only visible and applicable for `forkopimda`, `eselon-ii`, and `eselon-iii`. For others, it is hidden and set to 0.
    - **Validation Feedback**: Admin forms use the `needs-validation` Bootstrap pattern for real-time validity checks. Error messages are displayed **inline** under each specific field with red border highlights.
- **Frontend Display**:
    - **Sequential Numbering**: Tables in the frontend profile list include a "No" column.
    - **Column Layout**: In the Lurah and Kepala Desa sections, a dedicated "Kecamatan" column is shown at the end of the table.

## 🛠 Database & System
- **Profile Slug Generation**: Automatic unique slug generation is reserved ONLY for `bupati`, `wakil-bupati`, and `sekda` types to support their dedicated profile detail pages. For all other types, the slug is set to `NULL`.
- **Proxy API**: Internal routes `/admin/profiles/get_kecamatan` and `/admin/profiles/get_wilayah` act as proxies to bypass CORS when fetching geographical data from `apps.sinjaikab.go.id`.
- **Validation**: Image uploads are optional for regional and eselon types. Mandatory fields for all profiles include `name`, `type`, and `position`. `Kecamatan` and `institution` are mandatory for regional profiles.
- **SQL Export & Updates**: 
    - A clean SQL dump utility exists to export personnel data without IDs for production synchronization.
    - Cumulative database changes are tracked in `database_updates_YYYY_MM_DD.sql` files for manual synchronization.

## 🎨 Layout Standards
- **Component Inclusion**: Header and footer partials have been merged directly into `app/Views/layouts/frontend.php` to prevent redundant tags and improve render speed as per user preference.
