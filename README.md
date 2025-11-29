# Bookstore - Website Bán Sách Trực Tuyến

Đây là một dự án website thương mại điện tử được xây dựng bằng PHP thuần, tập trung vào việc bán sách online. Trang web cung cấp đầy đủ các chức năng cho cả người dùng và quản trị viên, từ việc xem sản phẩm, giỏ hàng, thanh toán cho đến quản lý sản phẩm, danh mục và đơn hàng.

## Mục lục
- [Tính năng chính](#tính-năng-chính)
- [Công nghệ sử dụng](#công-nghệ-sử-dụng)
- [Cài đặt và Chạy dự án](#cài-đặt-và-chạy-dự-án)
- [Cấu trúc thư mục](#cấu-trúc-thư-mục)

## Tính năng chính

### Dành cho Người dùng (Khách hàng)
- **Đăng ký và Đăng nhập:** Người dùng có thể tạo tài khoản mới và đăng nhập vào hệ thống.
- **Duyệt sản phẩm:** Xem danh sách sách theo danh mục hoặc tất cả sản phẩm.
- **Xem chi tiết sản phẩm:** Xem thông tin chi tiết, hình ảnh, mô tả và giá của từng cuốn sách.
- **Giỏ hàng:** Thêm sản phẩm vào giỏ hàng, cập nhật số lượng, xóa sản phẩm khỏi giỏ.
- **Danh sách yêu thích (Wishlist):** Lưu lại những sản phẩm yêu thích để xem lại sau.
- **Thanh toán:** Thực hiện thanh toán cho các sản phẩm trong giỏ hàng thông qua cổng thanh toán Stripe.
- **Lịch sử đơn hàng:** Xem lại các đơn hàng đã đặt.
- **Tải sách:** Sau khi thanh toán thành công, người dùng có thể tải xuống các sản phẩm sách (dạng file PDF, ebook...).

### Dành cho Quản trị viên (Admin)
- **Bảng điều khiển (Dashboard):** Giao diện quản trị riêng biệt và an toàn.
- **Quản lý Sản phẩm:** Thêm, sửa, xóa sách, cập nhật thông tin, giá cả và hình ảnh.
- **Quản lý Danh mục:** Thêm, sửa, xóa các danh mục sách.
- **Quản lý Quản trị viên:** Thêm, xóa các tài khoản quản trị viên khác.
- **Xem và xử lý đơn hàng:** (Chức năng có thể được mở rộng).

## Công nghệ sử dụng
- **Backend:** PHP (thuần, không sử dụng framework).
- **Frontend:** HTML, CSS.
- **Cơ sở dữ liệu:** MySQL (hoặc MariaDB).
- **Quản lý Dependencies:** Composer.
- **Cổng thanh toán:** [Stripe PHP SDK](https://github.com/stripe/stripe-php).
- **Gửi Email:** [PHPMailer](https://github.com/PHPMailer/PHPMailer) (có trong thư mục `src`).

## Cài đặt và Chạy dự án

### Yêu cầu
- Một môi trường server web như XAMPP, WAMP, hoặc LAMP.
- PHP phiên bản 7.x trở lên.
- Composer.
- MySQL.

### Các bước cài đặt
1.  **Clone repository:**
    ```bash
    git clone <your-repository-url>
    ```
    Hoặc tải file ZIP và giải nén vào thư mục `htdocs` của XAMPP.

2.  **Cài đặt dependencies:**
    Mở terminal hoặc command prompt trong thư mục gốc của dự án và chạy lệnh:
    ```bash
    composer install
    ```

3.  **Cơ sở dữ liệu:**
    - Mở phpMyAdmin hoặc một công cụ quản lý CSDL khác.
    - Tạo một cơ sở dữ liệu mới (ví dụ: `bookstore`).
    - Import file `SQL_FILE/Bookstore.sql` vào cơ sở dữ liệu vừa tạo.

4.  **Cấu hình kết nối:**
    - Mở file `config/config.php`.
    - Chỉnh sửa các hằng số `HOST`, `DBNAME`, `USER`, và `PASS` để khớp với thông tin cơ sở dữ liệu của bạn.
    ```php
    define("HOST", "localhost");
    define("DBNAME", "bookstore");
    define("USER", "root");
    define("PASS", ""); // Mật khẩu của bạn
    ```

5.  **Chạy server:**
    - Khởi động Apache và MySQL trong XAMPP.
    - Mở trình duyệt và truy cập vào địa chỉ: `http://localhost/bookstore`

## Cấu trúc thư mục

Dưới đây là giải thích về cấu trúc và vai trò của các thư mục và file chính trong dự án.

```
.
├── admin-panel/        # Chứa toàn bộ code cho trang quản trị
│   ├── admins/             # CRUD cho tài khoản admin
│   ├── categories-admins/  # CRUD cho danh mục
│   ├── products-admins/    # CRUD cho sản phẩm (sách)
│   ├── layouts/            # Header và Footer cho trang admin
│   └── index.php           # Trang dashboard chính của admin
│
├── auth/               # Xử lý đăng ký, đăng nhập, đăng xuất cho người dùng
│   ├── login.php
│   ├── logout.php
│   └── register.php
│
├── categories/         # Hiển thị sản phẩm theo danh mục
│   ├── single-category.php # Hiển thị sách trong một danh mục cụ thể
│   └── index.php           # (Có thể là trang liệt kê tất cả danh mục)
│
├── config/             # Chứa file cấu hình
│   └── config.php          # Cấu hình kết nối CSDL và các hằng số
│
├── includes/           # Chứa các thành phần tái sử dụng (header, footer) cho trang người dùng
│   ├── footer.php
│   └── header.php
│
├── shopping/           # Chứa các chức năng cốt lõi của việc mua sắm
│   ├── cart.php            # Trang giỏ hàng
│   ├── checkout.php        # Trang nhập thông tin thanh toán
│   ├── charge.php          # Xử lý thanh toán qua Stripe
│   ├── wishlist.php        # Trang danh sách yêu thích
│   ├── single.php          # Trang chi tiết một sản phẩm
│   ├── delete-item.php     # Xóa 1 sản phẩm khỏi giỏ hàng
│   ├── delete-all-item.php # Xóa tất cả sản phẩm khỏi giỏ hàng
│   └── update-item.php     # Cập nhật số lượng sản phẩm trong giỏ hàng
│
├── SQL_FILE/           # Chứa file dump của cơ sở dữ liệu
│   └── Bookstore.sql
│
├── src/                # Chứa các thư viện PHP (PHPMailer)
│
├── styles/             # Chứa các file CSS
│   └── theme.css           # CSS cho trang người dùng
│
├── users/              # Các trang dành riêng cho người dùng đã đăng nhập
│   ├── orders.php          # Lịch sử đơn hàng
│   └── wishlist-user.php   # Danh sách yêu thích của người dùng
│
├── vendor/             # Thư mục chứa các thư viện được cài đặt bởi Composer (vd: Stripe)
│
├── index.php           # Trang chủ của website
├── contact.php         # Trang liên hệ
├── download.php        # Xử lý logic cho phép người dùng tải sách đã mua
└── ...
```
