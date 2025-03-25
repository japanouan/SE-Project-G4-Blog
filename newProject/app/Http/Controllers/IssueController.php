<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Issue;
use App\Models\Notifications;
use Illuminate\Support\Str;


class IssueController extends Controller
{
    public function create()
    {
        return view('issue.report_issue');
    }

    public function replyPage($id)
    {
        $issue = Issue::with('user')->findOrFail($id);
        return view('admin.issue.issue_reply',compact('issue'));
    }
    
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'reply' => 'required|string|max:1000',
        ]);
    
        $issue = Issue::findOrFail($id);
        if($issue->status == 'reported'){
            $issue->status = 'in_progress';
        }
        $issue->reply = $validated['reply'];
        $issue->save();
        return redirect()->route('admin.issue.show')->with('success', 'การตอบกลับถูกบันทึก');
    }

    public function issueReported($id)
    {
        $issue = Issue::findOrFail($id);
        return view('issue.issue_report',compact('issue'));
    }

    public function reportPage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:256',
            'description' => 'required|string|max:1000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);


        if ($request->hasFile('file')) {
            $filename = Str::random(40) . '.' . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('images/issue'), $filename);
            $filePath = 'images/issue/' . $filename;
        } else {
            $filePath = null;
        }
    
        // บันทึกปัญหาลงในฐานข้อมูล
        $issue = Issue::create([
            'user_id' => Auth::user()->user_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'reported',
            'file_path' => $filePath,
        ]);
    
        // ส่งการแจ้งเตือนให้ Admin
        $this->sendNotificationToAdmin($issue);
    
        return $this->showReportStatus();
    }
    
    private function sendNotificationToAdmin($issue)
    {
        // ส่งการแจ้งเตือนให้ Admin
        Notifications::create([
            'issue_id' => $issue->id,
            'message' => 'มีปัญหาถูกแจ้งจากผู้ใช้ กรุณาตรวจสอบ',
        ]);
    }
    
    public function updateStatus($issueId)
    {
        $issue = Issue::findOrFail($issueId);
        if($issue->status == 'reported'){
            $issue->status = 'in_progress';  // เปลี่ยนสถานะ
        }else{
            $issue->status = 'fixed';  // เปลี่ยนสถานะ
        }
        $issue->save();
    
        return back()->with('success', 'ปัญหาถูกแก้ไขและแจ้งให้ผู้ใช้ทราบ');
    }

    // show Notifications ของ admin
    public function showNotifications()
    {
        $notifications = Notifications::join('issues', 'notifications.issue_id', '=', 'issues.id')
                                        ->join('Users','issues.user_id' ,'=', 'Users.user_id')
                                        ->where('issues.status','!=', 'fixed')
                                        ->select(
                                            'notifications.issue_id as issue_id',
                                            'issues.created_at as created_at',
                                            'issues.title as title',
                                            'issues.status as status',
                                            'Users.name as username',
                                            'Users.user_id as user_id',
                                            'issues.description as description',
                                            'issues.id as id'
                                        )->get();

        // dd($notifications);
        
        return view('admin.issue.index', compact('notifications'));
    }

    // show Notifications ของ user
    public function showReportStatus()
    {
        $notifications = auth()->user()->issue()->get();
        // dd($notifications);
        
        return view('notifications.index', compact('notifications'));
    }

    
    public function readNotification($notificationId)
    {
        $notification = Notifications::findOrFail($notificationId);
        $notification->is_read = true;
        $notification->user_id = Auth::user()->user_id;
        $notification->save();

        return redirect()->route('issues.show', $notification->issue_id);
    }

    public function shopownerIndex()
    {
        $issues = Issue::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('shopowner.issue.index', compact('issues'));
    }

    public function shopownerCreate()
    {
        return view('shopowner.issue.report');
    }

    public function shopownerStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:256',
            'description' => 'required|string|max:1000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filename = Str::random(40) . '.' . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('images/issue'), $filename);
            $filePath = 'images/issue/' . $filename;
        }

        $issue = Issue::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'reported',
            'file_path' => $filePath,
        ]);
    
        // Send notification to admin
        $this->sendNotificationToAdmin($issue);
    
        return redirect()->route('shopowner.issue.index')->with('success', 'ระบบได้รับการแจ้งปัญหาของคุณเรียบร้อยแล้ว');
    }

    public function shopownerShow($id)
    {
        $issue = Issue::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
    
        return view('shopowner.issue.show', compact('issue'));
    }
}
