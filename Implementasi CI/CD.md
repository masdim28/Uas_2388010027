Tahapan Implementasi CI/CD Website Statis dan Dinamis Menggunakan GitHub Actions, Docker Hub, dan AWS EC2

1. Membuat Repository GitHub
   Kegiatan
   Membuat repository GitHub.
   Membuat folder:
   web-statis
   web-dinamis
   .github/workflows
   ![alt text](image.png)
   Tampilan repository GitHub.

Terlihat folder:

web-statis
web-dinamis
.github

2. siapkan website dinamis dan statisnya
3. Membuat Akun dan Repository Docker Hub
   ![alt text](<Screenshot 2026-06-04 232252.png>)
4. Membuat Workflow GitHub Actions
   Membuat:

deploy-statis.yml
deploy-dinamis.yml

5. Menambahkan GitHub Secrets
   ![alt text](<Screenshot 2026-06-04 110124.png>)
6. Membuat Instance AWS EC2
   Kegiatan
   Membuat instance Ubuntu.
   Membuka port:
   22
   80
   8000
   ![alt text](<Screenshot 2026-06-04 105841.png>)
7. Menginstal Docker dan Docker Compose pada EC2
   Kegiatan

Instal:

Docker Engine
Docker Compose
sudo apt-get update
sudo apt-get install -y ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo \
 "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
 $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
 sudo tee /etc/apt/sources.list.p/docker.list > /dev/null

sudo apt-get update

sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
![alt text](<Screenshot 2026-06-04 112617.png>) 8. Membuat docker-compose.yml
Menghubungkan:

Laravel
MariaDB
Website statis dan dinamis 9. Build dan Push Docker Image Otomatis
![alt text](<Screenshot 2026-06-02 212726.png>) 10. cek ip port 80
![alt text](image-1.png) 11. cek ip port 8000
![alt text](<Screenshot 2026-06-05 014623.png>) 12. login ke admin untuk mengecek dan uplode produk baru
![alt text](image-2.png) 13. cek bagian user apakah sudah berubah dan ada produk baru yg muncul
![alt text](<Screenshot 2026-06-05 020301.png>) 14. Alhamdulillah Berhasil
