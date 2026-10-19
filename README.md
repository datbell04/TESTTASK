# TESTTASK

Laravel 11 chạy trên PHP 8.4 bằng Docker. Tài khoản và đề thi nằm ở MySQL. Bài làm nằm ở MongoDB.

## Chạy dự án

Cần Docker Desktop. Bốn lệnh sau là đủ, lần đầu có thể mất vài phút vì phải build image và cài dependency:

```bash
git clone https://github.com/datbell04/TESTTASK.git
cd TESTTASK
docker compose up -d --build
docker compose exec app php artisan app:check-connections
```

Container PHP tự copy `.env`, tạo `APP_KEY`, rồi chạy `php artisan migrate --seed`. Mở http://localhost:8080 để xem trang Laravel.

Kết quả mong đợi của lệnh kiểm tra:

```text
MySQL: OK
MongoDB: OK
```

Tài khoản mẫu, mật khẩu đều là `password`:

| Email | Vai trò |
| --- | --- |
| admin@example.com | admin |
| student@example.com | student |

Đề thi mẫu: `Đề thi mẫu Laravel` (60 phút), do admin tạo. Một bài làm của student được ghi vào collection `submissions`.

Cổng trên máy host được đổi để khỏi đụng MongoDB hoặc MySQL cài sẵn:

| Dịch vụ | Trong Docker | Trên máy host |
| --- | --- | --- |
| Nginx | 80 | 8080 |
| MySQL 8.0 | 3306 | 3307 |
| MongoDB 7 | 27017 | 27018 |

App trong container vẫn nối `mysql:3306` và `mongo:27017`. Không cần bật MongoDB cài sẵn trên Windows.

Image PHP là 8.4 vì lock file kéo Symfony 8, và các gói đó yêu cầu PHP >= 8.4.1. Laravel 11 chạy được trên bản này, vẫn thỏa điều kiện PHP >= 8.2.

## Vì sao dữ liệu chia hai nơi

MySQL giữ dữ liệu có quan hệ cố định: tài khoản (`users.role`) và đề thi (`exams.created_by` trỏ về user). Cần khóa ngoại, email duy nhất, và migration.

MongoDB giữ bài làm. Một bài có nhiều câu trả lời, mỗi câu một dạng khác nhau (trắc nghiệm hoặc tự luận) nên để nguyên một document lồng nhau, khỏi tách thêm bảng và join.

## Những gì chưa làm được và vì sao

- Chưa có đăng nhập, màn hình làm bài, hay API sửa đề. Sáu task hiện tại chỉ yêu cầu dựng môi trường, nối hai database, dữ liệu mẫu và lệnh kiểm tra kết nối.
- Laravel 11 đã hết hạn hỗ trợ từ tháng 3/2026 và đang có security advisory. Composer từ chối cài cho đến khi tắt `policy.advisories.block`. Vẫn dùng Laravel 11 vì đề bắt buộc, không nâng lên Laravel 12.
- `mongodb/laravel-mongodb` khóa ở `5.8.2`. Bản `5.9` trở đi yêu cầu Laravel 12, không cài được cùng skeleton này.
