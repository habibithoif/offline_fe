<ul class="list-group list-group-flush">
    @foreach($menus as $item)
        <li class="list-group-item">
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                    <input type="checkbox" id="menu{{ $item['id'] }}" name="menu[]" value="{{ $item['id'] }}" data-parent="{{ $item['parent_id'] ?? '' }}">
                    <label for="menu{{ $item['id'] }}" style="font-size: 16px; font-weight: bold;">
                        <i class="{{ $item['icon'] }}"></i> 
                        {{ $item['display'] ?? 'Nama Tidak Tersedia' }} 
                        <span class="badge badge-info">Level {{ $level }}</span>
                        @if(isset($item['children']) && is_array($item['children']) && count($item['children']) > 0)
                            <button class="btn btn-link btn-sm float-right" type="button" data-toggle="collapse" data-target="#child{{ $item['id'] }}" aria-expanded="false" aria-controls="child{{ $item['id'] }}">
                                <i class="fas fa-angle-down"></i> 
                            </button>
                        @endif
                    </label>
                </div>
            </div>

            <!-- Akses List Section -->
            @if(!empty($item['akses']))
                <ul class="list-group list-group-flush pl-4">
                    @foreach(json_decode($item['akses'] ?? '[]', true) as $value)
                        <li class="list-group-item">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="akses{{ $item['id'] }}_{{ $value }}" name="akses[{{ $item['id'] }}][]" value="{{ $value }}">
                                    <label for="akses{{ $item['id'] }}_{{ $value }}" class="akses-label">
                                        <i class="fas fa-lock"></i> {{ ucfirst($value) }}
                                    </label>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            <!-- Collapsible Child Menu -->
            @if(isset($item['children']) && is_array($item['children']) && count($item['children']) > 0)
                <div class="collapse" id="child{{ $item['id'] }}">
                    @include('setting.roles.roles-akses-partials', ['menus' => $item['children'], 'level' => $level + 1])
                </div>
            @endif
        </li>
    @endforeach
</ul>
