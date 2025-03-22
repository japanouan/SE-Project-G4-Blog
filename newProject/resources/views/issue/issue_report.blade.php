<h2>ปัญหาที่ผู้ใช้รายงาน</h2>

<p><strong>หัวข้อ:</strong> {{ $issue->title }}</p>
<p><strong>อธิบายปัญหา:</strong> {{ $issue->description }}</p>
<p><strong>สถานะ:</strong> {{ $issue->status }}</p>
<p><strong>การอัพเดทล่าสุด:</strong> {{ $issue->updated_at }}</p>

<button onclick="window.history.back();" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
    Back
</button>