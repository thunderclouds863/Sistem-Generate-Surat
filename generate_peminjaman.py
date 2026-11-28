import mysql.connector
from datetime import datetime, timedelta
from docxtpl import DocxTemplate
import sys  # Untuk mengambil argumen dari command line
import os  # Untuk memanipulasi path folder

# Memeriksa apakah ID surat diterima sebagai argumen
if len(sys.argv) > 1:
    surat_id = sys.argv[1]
else:
    print("ID surat tidak diberikan!")
    sys.exit(1)

# Gunakan /tmp saat deploy container eksternal (always writable); lokal tetap di folder proyek
folder_path = "/tmp/hmti_surat/peminjaman" if os.getenv("DB_HOST") else "Surat Peminjaman"
os.makedirs(folder_path, exist_ok=True)

def get_indonesian_month(month):
    bulan = {
        1: "Januari", 2: "Februari", 3: "Maret", 4: "April",
        5: "Mei", 6: "Juni", 7: "Juli", 8: "Agustus",
        9: "September", 10: "Oktober", 11: "November", 12: "Desember"
    }
    return bulan.get(month, "Invalid month")

def format_waktu(waktu):
    if isinstance(waktu, timedelta):
        total_seconds = int(waktu.total_seconds())
        hours = total_seconds // 3600
        minutes = (total_seconds % 3600) // 60
        return f"{hours:02d}:{minutes:02d}"
    elif isinstance(waktu, str):
        try:
            time_obj = datetime.strptime(waktu, '%H:%M:%S')
            return time_obj.strftime('%H:%M')
        except ValueError:
            return waktu
    return "00:00"

class SuratGenerator:
    def __init__(self):
        try:
            db_host = os.getenv("DB_HOST", "127.0.0.1")
            conn_kwargs = dict(
                host=db_host,
                port=int(os.getenv("DB_PORT", "3306")),
                user=os.getenv("DB_USER", "root"),
                password=os.getenv("DB_PASSWORD", os.getenv("DB_PASS", "")),
                database=os.getenv("DB_NAME", os.getenv("DEMO_DB_NAME", "HMTI_System")),
                sql_mode="NO_ENGINE_SUBSTITUTION",
            )
            if db_host not in ("127.0.0.1", "localhost") and os.getenv("DB_SSL", "1") != "0":
                conn_kwargs["ssl_ca"] = os.getenv("DB_SSL_CA", "/etc/ssl/certs/ca-certificates.crt")
                conn_kwargs["ssl_verify_cert"] = True
            self.conn = mysql.connector.connect(**conn_kwargs)
            self.cursor = self.conn.cursor(dictionary=True)
        except mysql.connector.Error as e:
            raise Exception(f"Database connection error: {str(e)}")

    def generate_surat(self, surat_id):
        try:
            query = "SELECT * FROM surat_peminjaman WHERE id = %s"
            self.cursor.execute(query, (surat_id,))
            data = self.cursor.fetchone()

            if not data:
                raise ValueError("Data surat tidak ditemukan")

            hari_tanggal = data['tanggal']
            tanggal = hari_tanggal.strftime('%d')
            bulan = int(hari_tanggal.strftime('%m'))
            tahun = hari_tanggal.strftime('%Y')

            context = {
                'Tanggal': tanggal,
                'Bulan': get_indonesian_month(bulan),
                'Tahun': tahun,
                'Nomor_Surat': data['nomor_surat'],
                'Instansi': data['instansi'],
                'Lokasi_Instansi': data['lokasi_instansi'],
                'Nama_Kegiatan': data['nama_kegiatan'],
                'Waktu_Mulai': format_waktu(data['waktu_mulai']),
                'Waktu_Selesai': format_waktu(data['waktu_selesai']),
                'Tempat': data['tempat'],
                'Nama_Peminjaman': data['isi_peminjaman'],
                'Program_Kerja': data['program_kerja'],
                'bidang': data['bidang'],
                'Ketua_Panitia': data['nama_penanggung_jawab'],
                'NIM': data['nim_penanggung_jawab']

            }

            doc = DocxTemplate('surat-peminjaman.docx')
            doc.render(context)

            # Tentukan nama file dan lokasi penyimpanan
            output_filename = os.path.join(folder_path, f'surat_peminjaman_{surat_id}_{datetime.now().strftime("%Y%m%d_%H%M%S")}.docx')
            doc.save(output_filename)

            return output_filename

        except Exception as e:
            raise Exception(f"Error generating surat: {str(e)}")

        finally:
            if hasattr(self, 'cursor') and self.cursor:
                self.cursor.close()
            if hasattr(self, 'conn') and self.conn:
                self.conn.close()

if __name__ == "__main__":
    if len(sys.argv) > 1:
        surat_id = sys.argv[1]
        generator = SuratGenerator()
        output_file = generator.generate_surat(surat_id)
        print(output_file)  # Mengoutputkan path file yang telah disimpan di folder "Surat Permohonan"
