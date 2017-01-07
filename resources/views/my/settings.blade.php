@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Settings</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST">
                            <fieldset>
                                <legend>Amazon MWS Settings</legend>
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('merchant_id') ? ' has-error' : '' }}">
                                    <label for="merchant_id" class="col-md-4 control-label">Merchant ID</label>

                                    <div class="col-md-6">
                                        <input id="merchant_id" type="text" class="form-control" name="merchant_id" value="{{ old('merchant_id', $row->merchant_id) }}" placeholder="Amazon Merchant ID" pattern=".{14}" required autofocus>
                                        <span class="help-block">Exactly 14 characters</span>

                                        @if ($errors->has('merchant_id'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('merchant_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('marketplace_id') ? ' has-error' : '' }}">
                                    <label for="marketplace_id" class="col-md-4 control-label">Marketplace ID</label>

                                    <div class="col-md-6">
                                        <input id="marketplace_id" type="text" class="form-control" name="marketplace_id" value="{{ old('marketplace_id', $row->marketplace_id) }}" placeholder="Amazon Marketplace ID" pattern=".{13}" required>
                                        <span class="help-block">Exactly 13 characters</span>

                                        @if ($errors->has('marketplace_id'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('marketplace_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('key_id') ? ' has-error' : '' }}">
                                    <label for="key_id" class="col-md-4 control-label">Key ID</label>

                                    <div class="col-md-6">
                                        <input id="key_id" type="text" class="form-control" name="key_id" value="{{ old('key_id', $row->key_id) }}" placeholder="Amazon Key ID" pattern=".{20}" required>
                                        <span class="help-block">Exactly 20 characters</span>

                                        @if ($errors->has('key_id'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('key_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="secret_key" class="col-md-4 control-label">Secret Key</label>

                                    <div class="col-md-6">
                                        <input id="secret_key" type="text" class="form-control" name="secret_key" value="{{ old('secret_key', $row->secret_key) }}" placeholder="Amazon Secret Key" pattern=".{40}" required>
                                        <span class="help-block">Exactly 40 characters</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        {{ link_to_route('dashboard.index', 'Cancel', [], ['class' => 'btn btn-default']) }}
                                        <button type="submit" class="btn btn-primary">
                                            Save my settings
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
