<form class="form-horizontal" role="form" method="POST">
    <fieldset>
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('merchant_id') ? ' has-error' : '' }}">
            <label for="merchant_id" class="col-md-12">Seller ID</label>

            <div class="col-md-12">
                <input id="merchant_id" type="text" class="form-control" name="merchant_id"
                       value="{{ old('merchant_id', $row->merchant_id) }}" placeholder="Exactly 14 characters"
                       pattern=".{14}" required autofocus>
                {{--<span class="help-block">Exactly 14 characters</span>--}}

                @if ($errors->has('merchant_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('merchant_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('marketplace_id') ? ' has-error' : '' }}">
            <label for="marketplace_id" class="col-md-12">Marketplace ID</label>

            <div class="col-md-12">
                <input id="marketplace_id" type="text" class="form-control" name="marketplace_id"
                       value="{{ old('marketplace_id', $row->marketplace_id) }}" placeholder="Exactly 13 characters"
                       pattern=".{13}" required>
                {{--<span class="help-block">Exactly 13 characters</span>--}}

                @if ($errors->has('marketplace_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('marketplace_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('key_id') ? ' has-error' : '' }}">
            <label for="key_id" class="col-md-12">AWS Access Key ID</label>

            <div class="col-md-12">
                <input id="key_id" type="text" class="form-control" name="key_id"
                       value="{{ old('key_id', $row->key_id) }}" placeholder="Exactly 20 characters" pattern=".{20}" required>
                {{--<span class="help-block">Exactly 20 characters</span>--}}

                @if ($errors->has('key_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('key_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="secret_key" class="col-md-12">Secret Key</label>

            <div class="col-md-12">
                <input id="secret_key" type="text" class="form-control" name="secret_key"
                       value="{{ old('secret_key', $row->secret_key) }}" placeholder="Exactly 40 characters" pattern=".{40}"
                       required>
                {{--<span class="help-block">Exactly 40 characters</span>--}}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                {{--{{ link_to_route('dashboard.index', 'Cancel', [], ['class' => 'btn btn-default']) }}--}}
                <button type="submit" class="btn btn-primary form-control"> UPDATE </button>
            </div>
        </div>
    </fieldset>
</form>
