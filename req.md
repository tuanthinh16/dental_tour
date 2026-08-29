Tạo project Laravel cho website giới thiệu du lịch và các gói tour.

Mục tiêu:

* Landing page giới thiệu điểm đến và tour.
* Hiển thị danh sách tour/package kèm giá.
* Có trang chi tiết tour.
* Có form khách gửi yêu cầu tư vấn.
* Có admin CMS quản lý tour, điểm đến, nội dung và lead.
* Chưa cần booking engine hoặc payment ở version đầu.

Tech:

* Laravel stable.
* MySQL.
* Blade.
* Tailwind CSS.
* Eloquent trong `app/Models`.
* Controller → Service → Repository.
* Không dùng Repository Interface.
* Form Request validation.
* Logging ra file.
* Admin auth JWT.
* Role + Permission.
* Soft Delete cho dữ liệu nghiệp vụ.

Quy ước database:

* Tất cả bảng chính có:

  * id
  * created_at
  * updated_at
* Dữ liệu cần bật/tắt dùng `is_active`.
* Dữ liệu nghiệp vụ dùng `deleted_at` nếu cần soft delete.
* Giá dùng `decimal(12,2)`.

Các model chính:

1. AdminUser

* id
* name
* email
* password
* is_active
* created_at
* updated_at
* deleted_at

2. Role

* id
* name
* code
* is_active
* created_at
* updated_at

3. Permission

* id
* name
* code
* is_active
* created_at
* updated_at

4. admin_user_roles

* id
* admin_user_id
* role_id
* created_at
* updated_at

5. role_permissions

* id
* role_id
* permission_id
* created_at
* updated_at

6. Destination

* id
* name
* slug
* short_description nullable
* description nullable
* image_id nullable
* sort_order
* is_active
* created_at
* updated_at
* deleted_at

Ví dụ:

* Đà Nẵng
* Hội An
* Huế
* Nha Trang
* Phú Quốc

7. TourCategory

* id
* name
* slug
* sort_order
* is_active
* created_at
* updated_at
* deleted_at

Ví dụ:

* City Tour
* Island Tour
* Adventure
* Cultural Tour
* Luxury Tour
* Family Tour

8. Tour

* id
* category_id nullable
* destination_id nullable
* name
* slug
* short_description
* description
* duration_days
* duration_nights nullable
* base_price
* original_price nullable
* currency
* image_id nullable
* badge nullable
* is_featured
* sort_order
* is_active
* created_at
* updated_at
* deleted_at

9. TourItinerary

* id
* tour_id
* day_number
* title
* description
* sort_order
* is_active
* created_at
* updated_at
* deleted_at

10. TourIncludedItem

* id
* tour_id
* content
* sort_order
* is_active
* created_at
* updated_at
* deleted_at

Ví dụ:

* Hotel
* Breakfast
* Airport transfer
* Tour guide
* Entrance tickets

11. TourExcludedItem

* id
* tour_id
* content
* sort_order
* is_active
* created_at
* updated_at
* deleted_at

Ví dụ:

* Flight tickets
* Personal expenses
* Visa fee

12. ConsultationRequest

* id
* full_name
* email
* phone
* country nullable
* tour_id nullable
* travel_date nullable
* number_of_people nullable
* message nullable
* status
* utm_source nullable
* utm_medium nullable
* utm_campaign nullable
* created_at
* updated_at
* deleted_at

Status:

* new
* contacted
* completed
* cancelled

13. Page

* id
* title
* slug
* content
* seo_title nullable
* seo_description nullable
* is_active
* created_at
* updated_at
* deleted_at

14. Media

* id
* file_name
* file_path
* alt_text nullable
* is_active
* created_at
* updated_at
* deleted_at

15. Setting

* id
* key
* value nullable
* created_at
* updated_at

Quan hệ:

* Destination hasMany Tours.
* TourCategory hasMany Tours.
* Tour belongsTo Destination.
* Tour belongsTo TourCategory.
* Tour hasMany TourItineraries.
* Tour hasMany TourIncludedItems.
* Tour hasMany TourExcludedItems.
* ConsultationRequest belongsTo Tour nullable.
* Tour belongsTo Media nullable qua image_id.
* Destination belongsTo Media nullable qua image_id.
* AdminUser belongsToMany Role.
* Role belongsToMany Permission.

Cấu trúc source:

app/
Http/
Controllers/
Web/
HomeController.php
TourController.php
ConsultationController.php

```
  Admin/
    AuthController.php
    DashboardController.php
    DestinationController.php
    TourCategoryController.php
    TourController.php
    ConsultationRequestController.php
    PageController.php
    SettingController.php

Requests/
  Web/
  Admin/

Middleware/
```

Models/
AdminUser.php
Role.php
Permission.php
Destination.php
TourCategory.php
Tour.php
TourItinerary.php
TourIncludedItem.php
TourExcludedItem.php
ConsultationRequest.php
Page.php
Media.php
Setting.php

Services/
AuthService.php
DestinationService.php
TourService.php
ConsultationService.php
PageService.php
SettingService.php

Repositories/
DestinationRepository.php
TourRepository.php
ConsultationRepository.php
PageRepository.php
SettingRepository.php

Frontend:

* `resources/views/layouts/app.blade.php`
* `resources/views/home.blade.php`
* `resources/views/tours/index.blade.php`
* `resources/views/tours/show.blade.php`

Homepage gồm:

* Hero.
* Featured destinations.
* Featured tours.
* Tour packages + pricing.
* Why choose us.
* Consultation form.
* CTA.
* Footer.

Tour detail gồm:

* Tour name.
* Price.
* Duration.
* Destination.
* Description.
* Itinerary.
* Included.
* Excluded.
* Consultation CTA.

Admin CRUD:

* Destinations.
* Tour Categories.
* Tours.
* Tour Itinerary.
* Included items.
* Excluded items.
* Consultation Requests.
* Pages.
* Settings.

SoftDeletes dùng cho:

* AdminUser
* Destination
* TourCategory
* Tour
* TourItinerary
* TourIncludedItem
* TourExcludedItem
* ConsultationRequest
* Page
* Media

Role seed:

* super_admin
* admin
* editor

Permission seed:

* dashboard.view
* destinations.view
* destinations.create
* destinations.update
* destinations.delete
* tours.view
* tours.create
* tours.update
* tours.delete
* consultations.view
* consultations.update
* pages.view
* pages.create
* pages.update
* pages.delete
* settings.view
* settings.update
* admins.manage

Seed admin:

* name: admin
* email: [admin@example.com](mailto:admin@example.com)
* password: 123
* role: super_admin

Tạo riêng `InitialAdminSeeder`.

Seeder phải:

* kiểm tra email tồn tại trước khi insert
* không tạo trùng
* chỉ dùng bootstrap lần đầu
* có thể xóa file sau khi đã tạo admin
* không phụ thuộc runtime

Seed dữ liệu demo:

* 5 destinations.
* 4 tour categories.
* 8 tours.
* mỗi tour có itinerary.
* included/excluded items.
* settings cơ bản.

Routes public:

* GET `/`
* GET `/tours`
* GET `/tours/{slug}`
* POST `/consultation`

Admin prefix:

* `/admin`

JWT API:

* POST `/api/admin/auth/login`
* POST `/api/admin/auth/logout`
* POST `/api/admin/auth/refresh`
* GET `/api/admin/auth/me`

Logging:

* admin login success/fail
* admin CRUD
* consultation mới
* exception

Không log:

* password
* JWT
* token
* secret

Không thêm:

* booking engine
* payment
* hotel inventory
* flight inventory
* CRM
* accounting
* complex API

Giữ project đơn giản, dễ bảo trì, đúng mô hình:
Landing → Tour → Consultation Lead → Admin xử lý.
