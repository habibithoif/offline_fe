@foreach($items as $item)
    @php
        $hasChildren = !empty($item['children']);
        $isActive = request()->is(ltrim(parse_url(url($item['path']), PHP_URL_PATH), '/'));
    @endphp
    <li class="nav-item dropdown">
        <a href="{{ $hasChildren ? '#' : url($item['path']) }}" 
           class="nav-link dropdown-toggle {{ $isActive ? 'active' : '' }}"
           @if($hasChildren) data-toggle="dropdown" @endif>
            <i class="{{ $item['icon'] }} nav-icon"></i>
            <span>{{ $item['display'] }}</span>
        </a>
        @if($hasChildren)
            <ul class="dropdown-menu">
                @foreach($item['children'] as $child)
                    @php
                        $childHasChildren = !empty($child['children']);
                        $childActive = request()->is(ltrim(parse_url(url($child['path']), PHP_URL_PATH), '/'));
                    @endphp
                    <li class="dropdown-item {{ $childHasChildren ? 'dropdown-submenu' : '' }} {{ $childActive ? 'active' : '' }}">
                        <a href="{{ $childHasChildren ? '#' : url($child['path']) }}" class="dropdown-link">
                            <i class="{{ $child['icon'] }} nav-icon" style="width:18px;text-align:center;"></i>
                            {{ $child['display'] }}
                            @if($childHasChildren) <i class="fas fa-angle-right float-right" style="margin-top:3px;"></i> @endif
                        </a>
                        @if($childHasChildren)
                            <ul class="dropdown-menu">
                                @foreach($child['children'] as $subchild)
                                    @php
                                        $subActive = request()->is(ltrim(parse_url(url($subchild['path']), PHP_URL_PATH), '/'));
                                    @endphp
                                    <li class="dropdown-item {{ $subActive ? 'active' : '' }}">
                                        <a href="{{ url($subchild['path']) }}" class="dropdown-link">
                                            <i class="{{ $subchild['icon'] }} nav-icon" style="width:18px;text-align:center;"></i>
                                            {{ $subchild['display'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach
