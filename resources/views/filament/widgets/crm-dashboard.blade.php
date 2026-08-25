<x-filament::widget class="crm-dashboard-widget">
    @php
        $dashboard = $this->getDashboardData();
        $statMeta = [
            'Tổng khách hàng' => ['icon' => '👥', 'tone' => 'blue'],
            'Sản phẩm khách đang dùng' => ['icon' => '📦', 'tone' => 'cyan'],
            'Đơn trong kỳ' => ['icon' => '📅', 'tone' => 'amber'],
            'Đơn đang thực hiện' => ['icon' => '🔧', 'tone' => 'violet'],
            'Doanh thu trong kỳ' => ['icon' => '₫', 'tone' => 'green'],
        ];
    @endphp

    <div class="crm-dashboard">
        <div class="crm-dashboard__heading">
            <div>
                <p class="crm-dashboard__eyebrow">TỔNG QUAN {{ mb_strtoupper($this->getPeriodLabel()) }}</p>
                <h2>Hoạt động dịch vụ</h2>
            </div>
            <div class="crm-period-filter">
                <select wire:model="period" aria-label="Kiểu thống kê">
                    <option value="today">Ngày</option>
                    <option value="week">Tuần</option>
                    <option value="month">Tháng</option>
                    <option value="year">Năm</option>
                </select>
                <input type="date" wire:model="referenceDate" aria-label="Ngày thống kê">
            </div>
        </div>

        <div class="crm-stats">
            @foreach ($dashboard['stats'] as $label => $value)
                @php
                    $meta = $statMeta[$label];
                @endphp
                <div class="crm-stat crm-stat--{{ $meta['tone'] }}">
                    <div class="crm-stat__icon">{{ $meta['icon'] }}</div>
                    <div><div class="crm-stat__label">{{ $label }}</div><div class="crm-stat__value">{{ $value }}</div></div>
                </div>
            @endforeach
        </div>

        <div class="crm-panels">
            <section class="crm-panel">
                <div class="crm-panel__header">
                    <div><h3>Đơn dịch vụ gần nhất</h3><p>Theo dõi các đơn vừa cập nhật</p></div>
                    <a href="{{ route('filament.resources.service-orders.index') }}">Xem tất cả →</a>
                </div>
                <div class="crm-list">
                    @forelse($dashboard['recent'] as $order)
                        @php
                            $status = (string) $order->status;
                            $statusLabel = \App\Filament\Resources\ServiceOrderResource::statusLabels()[$status] ?? $status;
                        @endphp
                        <a class="crm-list__row" href="{{ route('filament.resources.service-orders.edit', $order) }}">
                            <div class="crm-list__main"><strong>{{ $order->order_code }}</strong><span>{{ optional($order->customer)->full_name ?: 'Chưa có khách hàng' }}</span></div>
                            <span class="crm-badge crm-badge--{{ str_replace('_', '-', $status) }}">{{ $statusLabel }}</span>
                        </a>
                    @empty
                        <div class="crm-empty">Chưa có đơn dịch vụ.</div>
                    @endforelse
                </div>
            </section>

            <section class="crm-panel">
                <div class="crm-panel__header"><div><h3>Lịch dịch vụ trong kỳ</h3><p>{{ $this->getPeriodLabel() }}</p></div></div>
                <div class="crm-list">
                    @forelse($dashboard['scheduled'] as $order)
                        <a class="crm-list__row" href="{{ route('filament.resources.service-orders.edit', $order) }}">
                            <span class="crm-time">{{ optional($order->scheduled_at)->format('H:i') }}</span>
                            <div class="crm-list__main"><strong>{{ optional($order->customer)->full_name ?: 'Chưa có khách hàng' }}</strong><span>{{ $order->order_code }} · {{ optional(optional($order->purifier)->product)->name ?: 'Chưa chọn sản phẩm' }}</span></div>
                        </a>
                    @empty
                        <div class="crm-empty"><span>✓</span> Không có lịch dịch vụ trong kỳ.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="crm-panel crm-panel--products">
            <div class="crm-panel__header">
                <div><h3>Top sản phẩm sử dụng nhiều</h3><p>Dựa trên số lượng sản phẩm trong đơn dịch vụ</p></div>
                <a href="{{ route('filament.resources.products.index') }}">Danh sách sản phẩm →</a>
            </div>
            <div class="crm-products">
                @forelse($dashboard['topProducts'] as $index => $product)
                    <div class="crm-product"><span class="crm-product__rank">{{ $index + 1 }}</span><strong>{{ $product->name }}</strong><span>{{ number_format($product->used_quantity, 0, ',', '.') }} lượt dùng</span></div>
                @empty
                    <div class="crm-empty">Chưa đủ dữ liệu sản phẩm từ các đơn dịch vụ.</div>
                @endforelse
            </div>
        </section>
    </div>
</x-filament::widget>
