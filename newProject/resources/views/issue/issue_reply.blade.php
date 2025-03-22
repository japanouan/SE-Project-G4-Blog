<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-xl border border-gray-200 mt-10">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">📝 การตอบกลับ</h2>

    <div class="space-y-2 text-gray-700">
        <p><strong>🔸 หัวข้อปัญหา:</strong> {{ $issue->title }}</p>
        <p><strong>📝 อธิบายปัญหา:</strong> {{ $issue->description }}</p>
        <p><strong>📌 สถานะ:</strong> 
            <span class="px-2 py-1 rounded-full 
                @if($issue->status == 'in_progress') bg-yellow-300 text-yellow-800 
                @elseif($issue->status == 'fixed') bg-green-300 text-green-800 
                @else bg-gray-100 text-gray-800 @endif">
                {{ $issue->status }}
            </span>
        </p>
    </div>

    <!-- ฟอร์มตอบกลับ -->
    <form method="POST" action="{{ route('admin.issue.reply', ['id' => $issue->id]) }}" class="mt-6">
        @csrf

        <div class="mb-4">
            <label for="reply" class="block text-sm font-medium text-gray-700 mb-1">💬 การตอบกลับ:</label>
            <textarea id="reply" name="reply" rows="4"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm resize-none p-3"
                placeholder="เขียนคำตอบของคุณที่นี่...">{{ old('reply', $issue->reply) }}</textarea>
        </div>

        <div class="mt-4 flex flex-wrap gap-3">
            <!-- ปุ่มกลับ -->
            <a href="{{ route('admin.issue.show') }}"
               class="bg-gray-500 text-white px-5 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                 กลับ
            </a>

            <!-- ปุ่มอัปเดตสถานะ -->
            @if ($issue->status != 'fixed')
            <button type="submit"
                    formaction="{{ route('admin.issue.updateStatus', ['id' => $issue->id]) }}"
                    class="bg-yellow-500 text-white px-5 py-2 rounded-md hover:bg-yellow-600 transition duration-200">
                 เพิ่มสถานะ
            </button>
            @endif

            <!-- ปุ่มบันทึกการตอบกลับ -->
            <button type="submit"
                    formaction="{{ route('admin.issue.reply', ['id' => $issue->id]) }}"
                    class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                 บันทึก
            </button>
        </div>
    </form>
</div>

</body>
</html>