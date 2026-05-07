<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * عرض الحجوزات الخاصة بالمستخدم مع حساب الأرباح
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();

        // حساب إجمالي الأرباح للحجوزات المؤكدة فقط
        $totalEarnings = $bookings->where('status', 'confirmed')->sum('amount');

        return view('bookings.index', compact('bookings', 'totalEarnings'));
    }

    public function create()
    {
        return view('bookings.create');
    }

    /**
     * حفظ الحجز الجديد والسعر
     */
    public function store(Request $request)
    {
        // التحقق من المدخلات
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        // ضمان وجود الأعمدة في قاعدة البيانات (Migration-on-the-fly)
        $this->ensureColumnsExist();

        // الحفظ مع ربط الحجز بالمستخدم الحالي
        $booking = Booking::create([
            'customer_name'  => $request->customer_name,
            'phone_number'   => $request->phone_number,
            'customer_email' => $request->customer_email,
            'amount'         => $request->amount,
            'status'         => 'pending',
            'store_id'       => 1,
            'user_id'        => Auth::id(),
        ]);

        // إرسال الإيميل للعميل
        if ($request->customer_email) {
            try {
                Mail::raw("تم استلام حجزك بمبلغ {$request->amount}.", function ($m) use ($request) {
                    $m->to($request->customer_email)->subject('تأكيد الحجز');
                });
            } catch (\Exception $e) {}
        }

        return redirect()->route('bookings.index')->with('success', 'تم تسجيل الحجز بنجاح!');
    }

    /**
     * تحديث حالة الحجز
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث الحالة!');
    }

    /**
     * تصدير البيانات إلى Excel (CSV)
     */
    public function exportExcel()
    {
        $bookings = Booking::where('user_id', Auth::id())->get();
        $fileName = 'تقرير_الحجوزات_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Expires"             => "0"
        ];

        $columns = ['الاسم', 'الهاتف', 'الإيميل', 'السعر', 'الحالة', 'التاريخ'];

        $callback = function() use($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // لدعم العربية في Excel
            fputcsv($file, $columns);

            foreach ($bookings as $b) {
                fputcsv($file, [$b->customer_name, $b->phone_number, $b->customer_email, $b->amount, $b->status, $b->created_at->format('Y-m-d')]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'تم الحذف!');
    }

    /**
     * وظيفة خاصة للتأكد من وجود الأعمدة
     */
    private function ensureColumnsExist()
    {
        $cols = ['phone_number', 'customer_email', 'store_id', 'user_id', 'amount'];
        foreach ($cols as $c) {
            if (!Schema::hasColumn('bookings', $c)) {
                Schema::table('bookings', function (Blueprint $table) use ($c) {
                    if ($c == 'amount') $table->decimal($c, 10, 2)->default(0);
                    elseif ($c == 'user_id' || $c == 'store_id') $table->unsignedBigInteger($c)->nullable();
                    else $table->string($c)->nullable();
                });
            }
        }
    }
}