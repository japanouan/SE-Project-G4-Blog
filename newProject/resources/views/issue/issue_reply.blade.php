<h2>การตอบกลับ</h2>

<p><strong>หัวข้อปัญหา:</strong> {{ $issue->title }}</p>
<p><strong>อธิบายปัญหา:</strong> {{ $issue->description }}</p>
<p><strong>สถานะ:</strong> {{ $issue->status }}</p>

<!-- ใช้ textarea สำหรับการตอบกลับ -->
<form method="POST" action="{{ route('admin.issue.reply', ['id' => $issue->id]) }}">
    @csrf
    <div>
        <label for="reply" class="block text-sm font-medium text-gray-700">การตอบกลับ:</label>
        <textarea id="reply" name="reply" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('reply', $issue->reply) }}</textarea>
    </div>

    <div class="mt-4 flex justify-start space-x-4">
        <!-- ปุ่มกลับ -->
        <button onclick="window.history.back();" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
            กลับ
        </button>

        <!-- ปุ่มอัปเดตสถานะ -->
        @if ($issue->status != 'fixed')
        <button type="submit" formaction="{{ route('admin.issue.updateStatus', ['id' => $issue->id]) }}" 
                class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition">
            เพิ่มสถานะ
        </button>
        @endif

        <!-- ปุ่มบันทึกการตอบกลับ -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
            บันทึก
        </button>
    </div>
</form>
