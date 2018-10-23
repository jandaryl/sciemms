@extends('layouts.frontend')

@section('title', __('labels.frontend.titles.contact'))

@section('body_class', 'page-contact')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <iframe src="https://maps.google.com/maps?width=100%&amp;height=600&amp;hl=en&amp;q=North%20Negros%20College%2C%20Cadiz%20City%2C%20Negros%20Occidental%20+(My%20Business%20Name)&amp;ie=UTF8&amp;t=&amp;z=14&amp;iwloc=B&amp;output=embed"
                    height="550" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="col-md-6">
            <form action="{{ route('contact') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">@lang('validation.attributes.name')</label>
                    {{ Form::bsText('name', null, ['required', 'placeholder' => __('validation.attributes.name')]) }}
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="postal_code">@lang('validation.attributes.postal_code')</label>
                            {{ Form::bsText('postal_code', null, ['placeholder' => __('validation.attributes.postal_code')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="city">@lang('validation.attributes.city')</label>
                            {{ Form::bsText('city', null, ['placeholder' => __('validation.attributes.city')]) }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">@lang('validation.attributes.email')</label>
                            {{ Form::bsEmail('email', null, ['required', 'placeholder' => __('validation.attributes.email')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone">@lang('validation.attributes.phone')</label>
                            {{ Form::bsTel('phone', null, ['placeholder' => __('validation.attributes.phone')]) }}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">@lang('validation.attributes.message')</label>
                    {{ Form::bsTextarea('message', null, ['required', 'placeholder' => __('validation.attributes.message'), 'rows' => 5]) }}
                </div>

                <div class="form-group">
                    {!! Form::captcha() !!}
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-default" value="@lang('buttons.send')">
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {!! Captcha::script() !!}
@endpush
