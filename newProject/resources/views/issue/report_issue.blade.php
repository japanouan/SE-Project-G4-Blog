<form action="{{ route('report.issue') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h2>แจ้งปัญหา</h2>
    <div>
        <label for="title">หัวข้อ:</label>
        <input type="text" name="title" id="title" required>
    </div>
    <div>
        <label for="description">อธิบายปัญหา:</label>
        <textarea name="description" id="description" required></textarea>
    </div>
    <div>
        <label for="file">ไฟล์ (รูปภาพ/PDF):</label>
        <input type="file" name="file" id="file" accept="image/*,application/pdf">
    </div>
    <button type="submit">ส่งปัญหา</button>
    <button onclick="window.history.back();" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
        Back
    </button>
</form>