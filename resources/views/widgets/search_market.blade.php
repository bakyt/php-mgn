<div class="navbar-collapse " id="navbar-collapse">
    <form class="searching-group" method="get" action="{{ route('search.market', $Market->slug) }}" role="search">
        <table class="search-container">
            <tbody>
            <tr>
                <td class="td-category-select">
                    <select class="search-category-selector select2" name="category">
                        <option value="-1">{{ __('app.anywhere') }}</option>
                        @foreach($categories as $key=>$cat)
                            <option  @if(request()->has('category') and request('category') == $key) selected @endif value="{{ $key }}">{{ $cat->section->$locale }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="{{ __('rent.search_market', ["name"=>$Market->name]) }}">
                    <input type="hidden" name="query_1" value="{{ request('query_1') }}">
                </td>
                <td class="td-search-btn">
                    <button type="submit" class="btn btn-primary btn-flat" title="{{ __('rent.search') }}"><i class="fa fa-search"></i> </button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>