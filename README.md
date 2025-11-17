# ĐỒ ÁN TỐT NGHIỆP: WEBSITE QUẢN LÝ VÀ BÁN E-BOOK TRỰC TUYẾN

Tài liệu này mô tả chi tiết về dự án website bán E-book, bao gồm mục tiêu, công nghệ sử dụng, kiến trúc hệ thống, cơ sở dữ liệu, các luồng xử lý chính, và hướng dẫn triển khai. Đây là tài liệu kỹ thuật và cũng là cẩm nang giúp sinh viên thực hiện nắm vững đồ án để chuẩn bị cho buổi bảo vệ.

---

# 1. Giới thiệu

### 1.1. Mục tiêu dự án
- **Xây dựng một hệ thống E-commerce hoàn chỉnh:** Tạo ra một website cho phép người dùng xem, tìm kiếm, mua và tải xuống các sản phẩm sách điện tử (E-book).
- **Xây dựng trang quản trị (Admin Panel):** Cung cấp giao diện cho quản trị viên để quản lý toàn bộ hệ thống, bao gồm quản lý sản phẩm, danh mục, đơn hàng và người dùng.
- **Áp dụng kiến thức chuyên ngành:** Vận dụng các kiến thức về PHP, MySQL, lập trình hướng đối tượng (thông qua PDO), và các công nghệ web hiện đại (Bootstrap, Stripe API) để xây dựng một ứng dụng thực tế.
- **Tối ưu trải nghiệm người dùng (UX):** Thiết kế giao diện thân thiện, dễ sử dụng, và quy trình mua hàng đơn giản, nhanh chóng.

### 1.2. Đối tượng sử dụng
- **Khách hàng:** Những người có nhu cầu đọc và mua sách điện tử trực tuyến. Họ có thể tìm kiếm sách theo danh mục, xem chi tiết, thêm vào giỏ hàng và thanh toán.
- **Quản trị viên (Admin):** Người chịu trách nhiệm vận hành website, quản lý nội dung (sách, danh mục), theo dõi đơn hàng và quản lý các tài khoản quản trị khác.

### 1.3. Vấn đề dự án giải quyết
- **Tạo kênh phân phối E-book hiệu quả:** Cung cấp một nền tảng tập trung để tác giả hoặc nhà xuất bản có thể bán và phân phối E-book đến tay người đọc một cách nhanh chóng.
- **Tự động hóa quy trình bán hàng:** Toàn bộ quy trình từ việc khách hàng chọn sách, thanh toán, đến việc nhận sản phẩm (link tải E-book) đều được tự động hóa, giảm thiểu sự can thiệp thủ công.
- **Quản lý tập trung:** Thay vì quản lý sản phẩm và đơn hàng qua các công cụ rời rạc (như Excel, email), hệ thống cung cấp một trang Admin duy nhất để quản lý mọi thứ.

---

# 2. Công nghệ sử dụng

- **Ngôn ngữ lập trình phía Server:** **PHP 7.4+**. Toàn bộ logic backend được xử lý bằng PHP.
- **Cơ sở dữ liệu:** **MySQL (MariaDB)**.
- **Giao tiếp CSDL:** **PDO (PHP Data Objects)**. Đây là một extension cung cấp giao diện nhất quán để truy cập nhiều loại CSDL khác nhau. Việc sử dụng PDO, đặc biệt là **Prepared Statements**, giúp tăng cường bảo mật, chống lại lỗi SQL Injection.
- **Frontend:** **HTML5, CSS3, JavaScript** và **Bootstrap 5**. Bootstrap được sử dụng làm CSS Framework chính để xây dựng giao diện responsive, đảm bảo website hiển thị tốt trên mọi thiết bị (desktop, tablet, mobile).
- **Quản lý Dependencies (PHP):** **Composer**. Dùng để cài đặt và quản lý các thư viện của bên thứ ba.
- **Thư viện & API:**
    - **Stripe PHP Library:** Tích hợp cổng thanh toán Stripe để xử lý các giao dịch thẻ tín dụng một cách an toàn và chuyên nghiệp.
    - **PHPMailer (dự đoán):** Dựa trên cấu trúc file trong `src/`, có thể hệ thống đã hoặc sẽ sử dụng PHPMailer để gửi email (ví dụ: xác nhận đơn hàng, reset mật khẩu).

---

# 3. Kiến trúc hệ thống

### 3.1. Sơ đồ tổng quan
Hệ thống được xây dựng theo mô hình Client-Server.

```
+-------------+      HTTP Request      +-------------------+      PHP/SQL      +----------------+
|   Client    | <--------------------> |    Web Server     | <---------------> |   Database     |
| (Trình duyệt)|                        | (XAMPP - Apache)  |                   | (MySQL)        |
+-------------+                        +-------------------+                   +----------------+
                                                |
                                                | Stripe API Call
                                                v
                                       +-----------------+
                                       |  Stripe Gateway |
                                       +-----------------+
```
- **Client:** Người dùng sử dụng trình duyệt web để tương tác với hệ thống.
- **Web Server:** Apache (trong bộ XAMPP) xử lý các HTTP request, thực thi các file PHP.
- **Database:** MySQL lưu trữ toàn bộ dữ liệu của ứng dụng.
- **Stripe Gateway:** Hệ thống bên ngoài xử lý giao dịch thanh toán.

### 3.2. Luồng dữ liệu chính (Ví dụ: Mua hàng)
1.  **Khách hàng** thêm sản phẩm vào giỏ hàng. Dữ liệu được lưu vào bảng `cart` trong CSDL, gắn với `user_id` của khách hàng.
2.  **Khách hàng** tiến hành thanh toán (`checkout.php`). Một form thanh toán của Stripe (Stripe Elements) được hiển thị.
3.  Thông tin thẻ của khách hàng được gửi trực tiếp đến **Stripe Server** và nhận về một `token` thanh toán an toàn.
4.  `token` này cùng thông tin đơn hàng được gửi đến file `charge.php` trên **Web Server**.
5.  `charge.php` sử dụng `token` để gọi API của Stripe, yêu cầu thực hiện thanh toán.
6.  Nếu Stripe xác nhận thanh toán thành công, `charge.php` sẽ lưu thông tin đơn hàng vào bảng `orders` trong **Database**.
7.  Hệ thống chuyển hướng người dùng đến trang `download.php` để tải E-book.

### 3.3. Phân tách các module
Dự án được tổ chức thành các module rõ ràng dựa trên cấu trúc thư mục:
- **`auth`**: Quản lý xác thực người dùng (Đăng ký, Đăng nhập, Đăng xuất).
- **`shopping`**: Xử lý các nghiệp vụ mua sắm (Giỏ hàng, Thanh toán, Lịch sử mua hàng).
- **`categories`**: Phân loại và hiển thị sản phẩm theo danh mục.
- **`admin-panel`**: Module quản trị, được tách biệt hoàn toàn với giao diện người dùng. Bao gồm các module con:
    - `admins`: Quản lý tài khoản quản trị viên.
    - `categories-admins`: Thêm, sửa, xóa danh mục.
    - `products-admins`: Thêm, sửa, xóa sản phẩm E-book.
- **`config`**: Chứa file cấu hình kết nối CSDL và các khóa API.
- **`includes` / `layouts`**: Chứa các thành phần giao diện tái sử dụng (header, footer).

---

# 4. Database

### 4.1. Sơ đồ ERD (Mô tả dựa trên SQL)
(Do không thể vẽ, phần này sẽ mô tả mối quan hệ giữa các bảng)

- **users (1) --- (N) orders**: Một người dùng có thể có nhiều đơn hàng.
- **users (1) --- (N) cart**: Một người dùng có thể có nhiều sản phẩm trong giỏ hàng.
- **categories (1) --- (N) products**: Một danh mục có thể có nhiều sản phẩm.
- **products (1) --- (N) cart**: Một sản phẩm có thể được thêm vào giỏ hàng của nhiều người dùng.

### 4.2. Mô tả chi tiết các bảng

| Tên bảng | Mô tả | Khóa chính | Khóa ngoại (Quan hệ) | 
| :--- | :--- | :--- | :--- | 
| **`users`** | Lưu thông tin tài khoản khách hàng. | `id` | | 
| **`admins`** | Lưu thông tin tài khoản quản trị viên. | `id` | | 
| **`categories`** | Lưu các danh mục sách. | `id` | | 
| **`products`** | Lưu thông tin chi tiết của từng E-book. | `id` | `category_id` -> `categories.id` | 
| **`cart`** | Lưu các sản phẩm trong giỏ hàng của người dùng. | `id` | `user_id` -> `users.id`, `pro_id` -> `products.id` | 
| **`orders`** | Lưu thông tin các đơn hàng đã thanh toán thành công. | `id` | `user_id` -> `users.id` | 

**Lưu ý khi bảo vệ:**
*   Trong file SQL, các khóa ngoại không được định nghĩa tường minh bằng `FOREIGN KEY CONSTRAINT`. Điều này có nghĩa là sự toàn vẹn dữ liệu được đảm bảo ở tầng ứng dụng (Application Level) thay vì tầng CSDL (Database Level). Đây là một lựa chọn thiết kế phổ biến trong các dự án nhỏ để đơn giản hóa, nhưng trong các hệ thống lớn, việc định nghĩa khóa ngoại ở CSDL sẽ chặt chẽ hơn.

### 4.3. Mô tả các query chính
- **Thêm người dùng (Đăng ký):**
  ```php
  // File: auth/register.php
  $insert = $conn->prepare("INSERT INTO users (username, email, mypassword) VALUES (:username, :email, :mypassword)");
  $insert->execute([
      ':username' => $username,
      ':email' => $email,
      ':mypassword' => password_hash($password, PASSWORD_DEFAULT), // Băm mật khẩu
  ]);
  ```
- **Lấy sản phẩm theo danh mục:**
  ```sql
  -- File: categories/single-category.php
  SELECT * FROM products WHERE category_id = :id
  ```
- **Lưu đơn hàng sau khi thanh toán:**
  ```php
  // File: shopping/charge.php
  $insert = $conn->prepare("INSERT INTO orders (email, username, fname, lname, token, price, user_id)
  VALUES(:email, :username, :fname, :lname, :token, :price, :user_id)");
  $insert->execute([...]);
  ```

---

# 5. Chi tiết code & logic

### 5.1. Giải thích các file PHP chính

- **`config/config.php`**:
    - Khởi tạo kết nối PDO đến CSDL.
    - Thiết lập chế độ báo lỗi `PDO::ERRMODE_EXCEPTION` để bắt lỗi SQL.
    - Lưu trữ khóa bí mật của Stripe.
- **`auth/login.php`**:
    - Nhận `email` và `password` từ form.
    - Truy vấn CSDL để tìm user có `email` tương ứng.
    - Dùng hàm `password_verify()` để so sánh mật khẩu người dùng nhập với mật khẩu đã băm trong CSDL.
    - Nếu thành công, lưu thông tin người dùng vào `$_SESSION` (ví dụ: `$_SESSION['user_id']`, `$_SESSION['username']`) để duy trì trạng thái đăng nhập.
- **`shopping/cart.php`**:
    - Truy vấn bảng `cart` để lấy tất cả sản phẩm của người dùng hiện tại (`user_id` lấy từ `$_SESSION`).
    - Hiển thị danh sách sản phẩm, tính tổng tiền và lưu vào `$_SESSION['price']`.
- **`shopping/charge.php`**:
    - **Đây là file xử lý logic quan trọng nhất.**
    - **Bảo mật:** Kiểm tra request phải là `POST` và người dùng phải đăng nhập.
    - **Thanh toán:**
        1.  Khởi tạo thư viện Stripe với `secret_key`.
        2.  Gọi `\Stripe\Charge::create()` với `source` là `$_POST['stripeToken']` và `amount` là tổng tiền.
    - **Lưu CSDL:**
        1.  Nếu thanh toán Stripe thành công, chuẩn bị câu lệnh `INSERT` vào bảng `orders`.
        2.  Sử dụng **prepared statement** để thực thi, đảm bảo an toàn.
    - **Hoàn tất:** Chuyển hướng người dùng đến trang tải sách.
- **`admin-panel/products-admins/create-products.php`**:
    - Hiển thị form cho phép admin nhập thông tin sách (tên, giá, mô tả, danh mục).
    - Xử lý việc upload file ảnh bìa và file E-book (PDF).
    - Lưu thông tin vào bảng `products` trong CSDL.

### 5.2. Flow xử lý chính: Thêm sách vào giỏ hàng
1.  Người dùng ở trang chi tiết sản phẩm (`single.php`).
2.  Nhấn nút "Add to cart".
3.  Form được submit đến file xử lý (ví dụ: `shopping/cart.php` hoặc một file riêng).
4.  File PHP nhận `pro_id`, `user_id` (từ session) và các thông tin khác.
5.  Kiểm tra xem sản phẩm đã có trong giỏ hàng của user chưa.
    - Nếu có, cập nhật số lượng (`pro_amount`).
    - Nếu chưa, thực hiện `INSERT` một dòng mới vào bảng `cart`.
6.  Chuyển hướng người dùng về trang giỏ hàng hoặc hiển thị thông báo thành công.

---

# 6. Giao diện (UI/UX)

### 6.1. Các màn hình chính
- **Trang chủ (`index.php`):** Hiển thị các sản phẩm mới, sản phẩm nổi bật.
- **Trang sản phẩm (`single.php`):** Hiển thị chi tiết thông tin một E-book, bao gồm ảnh bìa, mô tả, giá, và nút thêm vào giỏ hàng.
- **Trang giỏ hàng (`shopping/cart.php`):** Liệt kê các sản phẩm đã chọn, cho phép cập nhật số lượng, xóa sản phẩm, và hiển thị tổng tiền.
- **Trang thanh toán (`shopping/checkout.php`):** Form nhập thông tin giao hàng và form nhập thông tin thẻ tín dụng của Stripe.
- **Trang quản trị (`admin-panel/index.php`):** Bảng điều khiển tổng quan cho admin, thống kê nhanh về số lượng sách, người dùng, đơn hàng.
- **Các trang quản lý (trong `admin-panel`):** Giao diện dạng bảng, có các nút chức năng Thêm/Sửa/Xóa.

### 6.2. Thiết kế responsive
- Toàn bộ giao diện được xây dựng trên hệ thống lưới (Grid System) của **Bootstrap**.
- Sử dụng các class của Bootstrap như `.container`, `.row`, `.col-md-*`, `.col-lg-*` để đảm bảo layout tự động co giãn và sắp xếp lại trên các kích thước màn hình khác nhau.
- Menu điều hướng (Navbar) cũng là một thành phần responsive của Bootstrap, tự động thu gọn thành menu "hamburger" trên thiết bị di động.

### 6.3. Trải nghiệm người dùng
- **Luồng mua hàng đơn giản:** Người dùng có thể hoàn tất việc mua hàng chỉ trong vài bước (Xem sản phẩm -> Giỏ hàng -> Thanh toán -> Nhận hàng).
- **Phản hồi tức thì:** Sử dụng các thông báo (alert) để cho người dùng biết kết quả hành động của họ (ví dụ: "Thêm vào giỏ hàng thành công").
- **Giao diện quen thuộc:** Việc sử dụng các thành phần chuẩn của Bootstrap tạo ra một giao diện sạch sẽ, chuyên nghiệp và quen thuộc với đa số người dùng web.

---

# 7. Bảo mật

Đây là phần rất quan trọng khi bảo vệ. Hệ thống đã áp dụng các biện pháp sau:

- **Chống SQL Injection:**
    - **Biện pháp:** Sử dụng **PDO Prepared Statements** cho tất cả các truy vấn CSDL có dữ liệu đầu vào từ người dùng.
    - **Cách hoạt động:** PDO tách câu lệnh SQL ra khỏi dữ liệu. Dữ liệu được gửi đến CSDL một cách riêng biệt, do đó CSDL sẽ không bao giờ thực thi nó như một phần của câu lệnh SQL, vô hiệu hóa hoàn toàn các cuộc tấn công SQL Injection.
    - **Ví dụ (trong `auth/login.php`):**
      ```php
      $select = $conn->prepare("SELECT * FROM users WHERE email = :email");
      $select->execute([':email' => $email]);
      ```
- **Chống Cross-Site Scripting (XSS):**
    - **Biện pháp:** Sử dụng hàm `htmlspecialchars()` khi hiển thị bất kỳ dữ liệu nào do người dùng nhập ra trình duyệt.
    - **Cách hoạt động:** Hàm này chuyển đổi các ký tự đặc biệt của HTML (như `<` , `>`) thành các thực thể HTML (`&lt;`, `&gt;`). Điều này ngăn trình duyệt diễn giải các đoạn mã độc (thường là JavaScript) do kẻ tấn công chèn vào.
- **Băm mật khẩu (Password Hashing):**
    - **Biện pháp:** Sử dụng hàm `password_hash()` để băm mật khẩu trước khi lưu vào CSDL và `password_verify()` để kiểm tra mật khẩu khi đăng nhập.
    - **Cách hoạt động:** `password_hash()` tạo ra một chuỗi ký tự gần như không thể dịch ngược (one-way hashing) từ mật khẩu gốc. Thuật toán `PASSWORD_DEFAULT` (thường là BCRYPT) tự động thêm "salt", giúp cho việc tấn công bằng từ điển (dictionary attack) hoặc bảng cầu vồng (rainbow table) trở nên vô cùng khó khăn.
- **Bảo mật thanh toán (PCI Compliance):**
    - **Biện pháp:** Tích hợp với **Stripe Elements**.
    - **Cách hoạt động:** Thông tin thẻ nhạy cảm của người dùng được gửi trực tiếp từ trình duyệt của họ đến máy chủ của Stripe, không đi qua máy chủ của ứng dụng. Máy chủ chỉ nhận về một `token` an toàn để xử lý thanh toán. Điều này giúp ứng dụng tuân thủ tiêu chuẩn bảo mật PCI DSS.
- **Bảo vệ file/đường dẫn:**
    - **Biện pháp:** Kiểm tra phương thức request và trạng thái đăng nhập ở đầu các file xử lý nhạy cảm.
    - **Ví dụ (trong `shopping/charge.php`):**
      ```php
      // Chặn truy cập trực tiếp qua trình duyệt
      if($_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
          header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
          die( header( 'location: '.APPURL.'' ));
      }
      // Bắt buộc đăng nhập
      if(!isset($_SESSION['username'])) {
          header("location: ".APPURL."");
      }
      ```

---

# 8. Các thử thách & giải pháp

- **Thử thách:** Tích hợp một cổng thanh toán của bên thứ ba (Stripe) một cách an toàn.
    - **Giải pháp:** Đọc kỹ tài liệu của Stripe. Sử dụng Composer để cài đặt thư viện Stripe PHP. Triển khai luồng thanh toán với `Stripe Elements` và `token` để đảm bảo tuân thủ PCI. Xử lý các lỗi có thể xảy ra từ API của Stripe trong khối `try-catch`.
- **Thử thách:** Quản lý trạng thái đăng nhập của người dùng trên toàn bộ website.
    - **Giải pháp:** Sử dụng `$_SESSION` của PHP. Sau khi người dùng đăng nhập thành công, lưu `user_id` và `username` vào session. Ở đầu mỗi trang cần xác thực, kiểm tra sự tồn tại của các biến session này.
- **Thử thách:** Xử lý việc upload file (ảnh bìa, file E-book) một cách an toàn.
    - **Giải pháp:**
        1.  Kiểm tra loại file và kích thước file được upload.
        2.  Tạo ra một tên file mới, duy nhất (ví dụ: dùng `time()` hoặc `uniqid()`) để tránh bị ghi đè file hoặc các lỗi liên quan đến tên file chứa ký tự đặc biệt.
        3.  Lưu file vào một thư mục được chỉ định và lưu đường dẫn vào CSDL.
- **Thử thách:** Phân quyền giữa người dùng thường và quản trị viên.
    - **Giải pháp:** Xây dựng hai khu vực riêng biệt: giao diện người dùng và `admin-panel`. Trang admin sẽ có một cơ chế đăng nhập riêng (bảng `admins`) và kiểm tra session của admin ở tất cả các trang trong `admin-panel`.

---

# 9. Các tính năng nâng cao / cải tiến trong tương lai

- **Tìm kiếm nâng cao:** Thêm chức năng tìm kiếm E-book theo tên tác giả, nhà xuất bản, hoặc sử dụng Full-text search của MySQL để cải thiện tốc độ và độ chính xác.
- **Hệ thống đánh giá, bình luận:** Cho phép người dùng đã mua sách để lại đánh giá (rating) và bình luận về sản phẩm.
- **Gửi Email tự động:** Tích hợp PHPMailer để gửi email xác nhận đơn hàng cho khách, thông báo cho admin khi có đơn hàng mới.
- **Tối ưu hiệu năng:**
    - **Caching:** Áp dụng các kỹ thuật cache để giảm số lần truy vấn CSDL cho các dữ liệu ít thay đổi (ví dụ: danh sách danh mục).
    - **Tối ưu ảnh:** Nén ảnh bìa sách trước khi upload để giảm thời gian tải trang.
- **Thêm nhiều cổng thanh toán:** Tích hợp thêm các cổng thanh toán khác như PayPal, Momo, VNPay.
- **API cho ứng dụng di động:** Xây dựng một bộ API theo kiến trúc RESTful để dữ liệu có thể được sử dụng bởi một ứng dụng di động trong tương lai.

---

# 10. Hướng dẫn triển khai

### 10.1. Cài đặt môi trường
1.  **Web Server:** Cài đặt **XAMPP** (bao gồm Apache, MySQL, PHP). Link tải: [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)
2.  **Code Editor:** **Visual Studio Code** hoặc bất kỳ trình soạn thảo code nào bạn quen thuộc.
3.  **Composer:** Cài đặt Composer để quản lý thư viện PHP. Link tải: [https://getcomposer.org/](https://getcomposer.org/)

### 10.2. Hướng dẫn chạy project
1.  Clone hoặc tải mã nguồn của dự án về máy.
2.  Copy toàn bộ thư mục dự án vào `C:\xampp\htdocs\`.
3.  Mở Command Prompt hoặc Terminal trong thư mục gốc của dự án và chạy lệnh sau để cài đặt các thư viện cần thiết (như Stripe):
    ```bash
    composer install
    ```
4.  Khởi động XAMPP Control Panel, bật **Apache** và **MySQL**.
5.  Truy cập `http://localhost/phpmyadmin/`.

### 10.3. Hướng dẫn cấu hình database
1.  Tại trang phpMyAdmin, tạo một CSDL mới với tên là `bookstore`.
2.  Chọn CSDL `bookstore` vừa tạo, vào tab "Import".
3.  Nhấn "Choose File" và chọn file `SQL_FILE/Bookstore.sql` trong thư mục dự án.
4.  Nhấn "Go" để import. Quá trình này sẽ tạo tất cả các bảng và chèn dữ liệu mẫu.
5.  Mở file `config/config.php` và đảm bảo các thông tin kết nối CSDL là chính xác (thường là đúng với mặc định của XAMPP).
    ```php
    $host = "localhost";
    $dbname = "Bookstore";
    $user = "root";
    $pass = "";
    ```
6.  **Cấu hình Stripe:**
    - Đăng ký tài khoản Stripe.
    - Lấy `Secret Key` từ Dashboard của Stripe.
    - Thay thế giá trị `YOUR_STRIPE_SECRET_KEY` trong file `config/config.php` bằng key của bạn.
    - Lấy `Publishable Key` và thay thế vào các file frontend có form thanh toán (thường là `checkout.php`).

### 10.4. Truy cập website
- **Trang người dùng:** `http://localhost/bookstore/` (thay `bookstore` bằng tên thư mục dự án của bạn).
- **Trang quản trị:** `http://localhost/bookstore/admin-panel/`
    - **Tài khoản admin mẫu (dựa trên file SQL):**
        - Email: `admin.first@yahoo.com`
        - Mật khẩu: (Bạn cần xem lại code hoặc tạo admin mới vì mật khẩu đã được hash, hoặc dùng tài khoản có sẵn nếu bạn nhớ mật khẩu).

---

# 11. FAQ / Chuẩn bị bảo vệ

Đây là danh sách các câu hỏi giảng viên có thể hỏi và gợi ý cách trả lời.

**Câu 1: Tại sao bạn chọn PHP và PDO cho dự án này?**
> **Trả lời:** Em chọn PHP vì đây là ngôn ngữ lập trình phía server phổ biến, có cộng đồng lớn, và rất phù hợp để phát triển web nhanh. Đặc biệt, em chọn sử dụng PDO (PHP Data Objects) thay vì MySQLi vì PDO cung cấp một lớp trừu tượng hóa CSDL, giúp code dễ dàng chuyển đổi sang các hệ quản trị CSDL khác như PostgreSQL trong tương lai. Quan trọng hơn, PDO hỗ trợ mạnh mẽ **Prepared Statements**, là phương pháp tốt nhất để chống lại các cuộc tấn công SQL Injection, giúp đảm bảo an toàn cho dữ liệu của hệ thống.

**Câu 2: Bạn đã làm thế nào để bảo mật mật khẩu người dùng?**
> **Trả lời:** Em không lưu mật khẩu dưới dạng văn bản thuần. Thay vào đó, em sử dụng hàm `password_hash()` của PHP để băm mật khẩu trước khi lưu vào CSDL. Hàm này sử dụng thuật toán BCRYPT mạnh và tự động thêm "salt" để chống lại các phương pháp tấn công từ điển. Khi người dùng đăng nhập, em dùng hàm `password_verify()` để so sánh mật khẩu họ nhập với chuỗi đã băm. Đây là phương pháp bảo mật mật khẩu được khuyến nghị hiện nay.

**Câu 3: Luồng xử lý thanh toán của bạn hoạt động như thế nào? Làm sao để đảm bảo an toàn cho thông tin thẻ của khách hàng?**
> **Trả lời:** Em đã tích hợp cổng thanh toán Stripe. Luồng xử lý rất an toàn vì em đã áp dụng kiến trúc tuân thủ PCI. Cụ thể:
> 1. Ở trang thanh toán, thông tin thẻ của khách hàng được gửi trực tiếp từ trình duyệt của họ đến máy chủ Stripe thông qua JavaScript (Stripe.js).
> 2. Máy chủ của em **không bao giờ** nhận hay lưu trữ thông tin thẻ.
> 3. Stripe xử lý thông tin và trả về một `token` đại diện cho giao dịch.
> 4. Máy chủ của em chỉ sử dụng `token` này để yêu cầu Stripe thực hiện thanh toán. 
> Bằng cách này, toàn bộ gánh nặng bảo mật thông tin thẻ đã được Stripe xử lý, giúp hệ thống của em an toàn hơn rất nhiều. (Bạn có thể mở file `shopping/charge.php` để minh họa).

**Câu 4: Trong cấu trúc CSDL, tại sao bạn không định nghĩa khóa ngoại (Foreign Key)?**
> **Trả lời:** Trong dự án này, em đã quyết định quản lý các mối quan hệ logic ở tầng ứng dụng (application-level) thay vì ràng buộc cứng ở tầng CSDL. Ví dụ, khi xóa một danh mục, em sẽ viết code PHP để kiểm tra xem có sản phẩm nào thuộc danh mục đó không trước khi xóa. Cách tiếp cận này giúp đơn giản hóa việc phát triển và thay đổi CSDL trong giai đoạn đầu. Tuy nhiên, em cũng nhận thấy rằng trong các hệ thống lớn và phức tạp hơn, việc định nghĩa khóa ngoại ở CSDL sẽ giúp đảm bảo tính toàn vẹn dữ liệu một cách chặt chẽ và tự động hơn.

**Câu 5: Nếu có hai người dùng cùng lúc mua sản phẩm cuối cùng, hệ thống của bạn xử lý ra sao? (Race Condition)**
> **Trả lời:** Dạ, đây là một vấn đề về "Race Condition". Trong phạm vi của đồ án hiện tại, hệ thống chưa xử lý triệt để tình huống này. Luồng xử lý hiện tại là "last write wins" (ai thanh toán sau thì thông tin sẽ được ghi đè).
> **Để cải tiến trong tương lai,** em có thể áp dụng cơ chế "locking". Ví dụ, khi một người dùng bắt đầu tiến trình thanh toán cho sản phẩm cuối cùng, em có thể đánh dấu sản phẩm đó là "đang giao dịch" trong CSDL. Nếu người dùng khác cố gắng mua, hệ thống sẽ báo sản phẩm tạm thời hết hàng. Hoặc em có thể sử dụng các giao dịch CSDL (Database Transactions) để đảm bảo tính toàn vẹn: chỉ khi toàn bộ quá trình (trừ tiền, tạo đơn hàng, giảm số lượng sản phẩm) thành công thì giao dịch mới được commit.

**Câu 6: Bạn đã làm gì để website có giao diện đẹp và chạy được trên điện thoại?**
> **Trả lời:** Em đã sử dụng **Bootstrap**, một CSS framework rất mạnh. Nhờ hệ thống lưới (Grid System) của Bootstrap, em có thể dễ dàng chia layout thành các cột và các hàng. Các thành phần này sẽ tự động sắp xếp lại một cách hợp lý khi kích thước màn hình thay đổi, từ desktop xuống tablet và mobile. Em cũng đã sử dụng các component có sẵn của Bootstrap như Navbar, Card, Form để xây dựng giao diện một cách nhanh chóng, nhất quán và chuyên nghiệp.
