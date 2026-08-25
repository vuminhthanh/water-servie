<?php
namespace App\Filament\Widgets;
use App\Models\{Customer,Product,ServiceOrder,WaterPurifier};use Carbon\Carbon;use Filament\Widgets\Widget;use Illuminate\Support\Facades\DB;
class CrmDashboard extends Widget
{
    public static $sort=-100;
    public static $view='filament.widgets.crm-dashboard';
    public $period='today';
    public $referenceDate;

    public function mount(){$this->referenceDate=today()->toDateString();}

    public function getPeriodRange()
    {
        try{$date=Carbon::parse($this->referenceDate?:today())->startOfDay();}catch(\Throwable $e){$date=today();}
        if($this->period==='week')return[$date->copy()->startOfWeek(),$date->copy()->endOfWeek()];
        if($this->period==='month')return[$date->copy()->startOfMonth(),$date->copy()->endOfMonth()];
        if($this->period==='year')return[$date->copy()->startOfYear(),$date->copy()->endOfYear()];
        return[$date->copy()->startOfDay(),$date->copy()->endOfDay()];
    }

    public function getPeriodLabel()
    {
        [$from,$to]=$this->getPeriodRange();
        if($this->period==='week')return'Tuần '.$from->format('d/m').' - '.$to->format('d/m/Y');
        if($this->period==='month')return'Tháng '.$from->format('m/Y');
        if($this->period==='year')return'Năm '.$from->format('Y');
        return'Ngày '.$from->format('d/m/Y');
    }

    public function getDashboardData()
    {
        $range=$this->getPeriodRange();
        return [
            'stats'=>[
                'Tổng khách hàng'=>Customer::count(),
                'Sản phẩm khách đang dùng'=>WaterPurifier::where('status','active')->count(),
                'Đơn trong kỳ'=>ServiceOrder::whereBetween('created_at',$range)->count(),
                'Đơn đang thực hiện'=>ServiceOrder::where('status','in_progress')->count(),
                'Doanh thu trong kỳ'=>number_format(ServiceOrder::where('status','completed')->whereBetween('completed_at',$range)->sum('total_amount'),0,',','.').' ₫',
            ],
            'recent'=>ServiceOrder::with(['customer','assignments.technician'])->whereBetween('created_at',$range)->latest()->limit(8)->get(),
            'scheduled'=>ServiceOrder::with(['customer','purifier.product'])->whereBetween('scheduled_at',$range)->orderBy('scheduled_at')->limit(10)->get(),
            'topProducts'=>Product::query()->join('service_order_items','products.id','=','service_order_items.product_id')->join('service_orders','service_orders.id','=','service_order_items.service_order_id')->whereBetween('service_orders.created_at',$range)->select('products.id','products.name',DB::raw('SUM(service_order_items.quantity) as used_quantity'))->groupBy('products.id','products.name')->orderByDesc('used_quantity')->limit(5)->get(),
        ];
    }
}
