@extends('layouts.app')
@section('title', 'ویرایش تراکنش:' . $transaction->id ." - ")

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-{{ config('platform.sidebar-size') }}">
            @include('admin.sidebar')
        </div>
        <div class="col-md-{{ config('platform.content-size') }}">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ config('platform.name') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">مدیریت سیستم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.transaction') }}">تراکنش ها</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> ویرایش تراکنش: {{ $transaction->id }}</li>

                </ol>
            </nav>

            <div class="card card-default">
                <div class="card-header">
                    ویرایش تراکنش: {{ $transaction->id }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.transaction.update',['id' => $transaction->id]) }}"
                          onsubmit="$('.price').unmask();">
                        @csrf
                        @method('post')
                        @if(Request::segment(4) == 'income')
                            <input type="hidden" name="type" value="income"/>
                        @elseif(Request::segment(4) == 'expense')
                            <input type="hidden" name="type" value="expense"/>
                        @endif
                        <div class="form-group">
                            <label for="amount">مبلغ</label>
                            <div class="input-group mb-2 ml-sm-2">
                                <input id="amount" type="text" dir="ltr"
                                       class="price form-control{{ $errors->has('amount',$transaction->amount) ? ' is-invalid' : '' }}"
                                       name="amount" value="{{ old('amount',\App\Utils\MoneyUtil::display($transaction->amount)) }}" required
                                       autofocus>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">{{ trans('currency.'.config('platform.currency')) }}</div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="category_id">دسته</label>

                            <select name="category_id" id="category_id" class="form-control selector">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"{{ old('category_id',$transaction->category_id) == $category->id  ? ' selected' : '' }}>{{$category->title}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('category_id'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('category_id') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="transaction_at">تاریخ</label>
                            <div dir="rtl">
                                <date-picker
                                        id="transaction_at"
                                        name="transaction_at"
                                        format="jYYYY/jMM/jDD"
                                        display-format="jYYYY/jMM/jDD"
                                        color="#6838b8"
                                        type="date"
                                        value="{{ old('transaction_at', jdate($transaction->transaction_at)->format("Y/m/d")) }}"
                                        placeholder="____/__/__">
                                </date-picker>
                            </div>

                            @if ($errors->has('transaction_at'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('transaction_at') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="account_id">حساب</label>

                            <select name="account_id" id="account_id" class="selector form-control">
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}"{{ old('account_id',$transaction->account_id) == $account->id  ? ' selected' : '' }}>{{$account->title}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('account_id'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('account_id') }}</strong>
                                    </span>
                            @endif
                        </div>



                        <div class="form-group">
                            <label for="user_id">شخص</label>

                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">بدون شخص</option>
                                <option value="{{ $transaction->user->id }}" selected>{{ $transaction->user->name }}</option>
                            </select>
                            @if ($errors->has('user_id'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description">توضیحات</label>

                            <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                      name="description"
                                      id="description"> {{ old('description',$transaction->description) }}</textarea>

                            @if ($errors->has('description'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <label for="tags">برچسب ها<span
                                        class="font-weight-light font-italic"> - اختیاری</span></label>
                            <select id="tags" name="tags[]" class="form-control tags" multiple="multiple">
                            </select>
                            @if ($errors->has('tags'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('tags') }}</strong>
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-edit"></i>
                            ویرایش تراکنش
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $("#user_id").select2({
            dir: "rtl",
            language: "fa",
            ajax: {
                url: "{{ route('admin.ajax.users') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term, // search term
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data.data, function(item) {
                            if(item.title) {
                                return { id: item.id, text: item.first_name + ' ' + item.last_name + '(' + item.title + ')' };
                            } else {
                                return { id: item.id, text: item.first_name + ' ' + item.last_name };
                            }

                        })
                    };
                },
                cache: true
            },
            placeholder: 'جستجوی شخص',
            minimumInputLength: 3,
        });
    </script>
@endsection
