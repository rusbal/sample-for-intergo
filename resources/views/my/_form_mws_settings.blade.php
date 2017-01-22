<form class="form-horizontal" role="form" method="POST">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('merchant_id') ? ' has-error' : '' }}">
            <label for="merchant_id" class="col-md-12">Seller ID</label>

            <div class="col-md-12">
                <input id="merchant_id" type="text" class="form-control" name="merchant_id"
                       value="{{ old('merchant_id', $row->merchant_id) }}" placeholder="Exactly 14 characters"
                       pattern=".{14}" required autofocus>

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
                       value="ATVPDKIKX0DER" placeholder="Exactly 13 characters"
                       pattern=".{13}" required readonly>

                @if ($errors->has('marketplace_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('marketplace_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('mws_auth_token') ? ' has-error' : '' }}">
            <label for="mws_auth_token" class="col-md-12">MWS Auth Token</label>

            <div class="col-md-12">
                <textarea name="mws_auth_token" id="mws_auth_token" cols="30" rows="2" class="form-control"
                          placeholder="amzn.mws.long-code-here" required>{{ old('mws_auth_token', $row->mws_auth_token) }}</textarea>
                @if ($errors->has('mws_auth_token'))
                    <span class="help-block">
                        <strong>{{ $errors->first('mws_auth_token') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary form-control"> UPDATE </button>
            </div>
        </div>
</form>
