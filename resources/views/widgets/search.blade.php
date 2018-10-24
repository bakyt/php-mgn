<div class="navbar-collapse " id="navbar-collapse">
    <form method="get" action="{{ route('search') }}" role="search">
        <table class="search-container">
            <tbody>
            <tr>
                <td class="td-category-select">
                    <select class="search-category-selector select2" name="category">
                        <option value="0">{{ __('app.anywhere') }}</option>
                        @foreach($categories as $cat)
                            <option  @if(request('category') == $cat->id) selected @endif value="{{ $cat->id }}">{{ $cat->name->$locale }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="{{ __('rent.what_you_want_to_rent') }}">
                </td>
                <td class="td-search-btn">
                    <button type="submit" class="btn btn-primary btn-flat" title="{{ __('rent.search') }}"><i class="fa fa-search"></i> </button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>