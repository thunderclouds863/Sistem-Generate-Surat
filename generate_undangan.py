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

# Pastikan folder "Surat Undangan" ada, jika belum buat
folder_path = "Surat Undangan"
if not os.path.exists(folder_path):
    os.makedirs(folder_path)

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
            self.conn = mysql.connector.connect(
                host=os.getenv("DB_HOST", "127.0.0.1"),
                port=int(os.getenv("DB_PORT", "3306")),
                user=os.getenv("DB_USER", "root"),
                password=os.getenv("DB_PASS", ""),
                database=os.getenv("DB_NAME", "HMTI_System")
            )
            self.cursor = self.conn.cursor(dictionary=True)
        except mysql.connector.Error as e:
            raise Exception(f"Database connection error: {str(e)}")

    def generate_surat(self, surat_id):
        try:
            query = "SELECT * FROM surat_undangan WHERE id = %s"
            self.cursor.execute(query, (surat_id,))
            data = self.cursor.fetchone()

            if not data:
                raise ValueError("Data surat tidak ditemukan")

            hari_tanggal = data['hari_tanggal']
            tanggal = hari_tanggal.strftime('%d')
            bulan = int(hari_tanggal.strftime('%m'))
            tahun = hari_tanggal.strftime('%Y')

            context = {
                'Tanggal': tanggal,
                'Bulan': get_indonesian_month(bulan),
                'Tahun': tahun,
                'Nomor_Surat': data['nomor_surat'],
                'Jabatan_Tamu_Undangan': data['jabatan_tamu'],
                'Nama_Tamu_Undangan': data['nama_tamu'],
                'Lokasi': data['tempat'],
                'Nama_Kegiatan': data['nama_kegiatan'],
                'Waktu_Mulai': format_waktu(data['waktu_mulai']),
                'Waktu_Selesai': format_waktu(data['waktu_selesai']),
                'Tempat': data['tempat'],
                'Agenda': data['agenda'],
                'Program_Kerja': data['nama_kegiatan'],
                'BIDANG_HMTI': data['bidang_organisasi'],
                'Ketua_Panitia': data['nama_penanggung_jawab'],
                'NIM': data['nim_penanggung_jawab']
            }

            doc = DocxTemplate('surat-undangan.docx')
            doc.render(context)

            # Tentukan nama file dan lokasi penyimpanan
            output_filename = os.path.join(folder_path, f'surat_undangan_{surat_id}_{datetime.now().strftime("%Y%m%d_%H%M%S")}.docx')
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
        print(output_file)  # Mengoutputkan path file yang telah disimpan di folder "Surat Undangan"
