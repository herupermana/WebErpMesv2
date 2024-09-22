@extends('adminlte::page')

@section('title', __('general_content.purchase_trans_key'))

@section('content_header')
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <x-Content-header-previous-button  h1="{{  __('general_content.purchase_trans_key') }} : {{  $Purchase->code }}" previous="{{ $previousUrl }}" list="{{ route('purchases') }}" next="{{ $nextUrl }}"/>
@stop

@section('right-sidebar')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<div class="card">
  <div class="card-header p-2">
    <ul class="nav nav-pills">
      <li class="nav-item"><a class="nav-link active" href="#Purchase" data-toggle="tab">{{  __('general_content.purchase_info_trans_key') }}</a></li> 
      <li class="nav-item"><a class="nav-link" href="#PurchaseLines" data-toggle="tab">{{  __('general_content.purchase_lines_trans_key') }} ({{ count($Purchase->PurchaseLines) }})</a></li>
      @if(count($CustomFields)> 0)
      <li class="nav-item"><a class="nav-link" href="#CustomFields" data-toggle="tab">{{ __('general_content.custom_fields_trans_key') }} ({{ count($CustomFields) }})</a></li>
      @endif
    </ul>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <div class="tab-content">
      <div class="tab-pane active" id="Purchase">
        
        @livewire('arrow-steps.arrow-purchase', ['PurchaseId' => $Purchase->id, 'PurchaseStatu' => $Purchase->statu])
        <div class="row">
          <div class="col-md-9">
            @include('include.alert-result')
            <form method="POST" action="{{ route('purchase.update', ['id' => $Purchase->id]) }}" enctype="multipart/form-data">
              <x-adminlte-card title="{{ __('general_content.informations_trans_key') }}" theme="primary" maximizable>
                @csrf 
                <div class="row">
                  <div class="form-group col-md-6">
                    <p><label for="code" class="text-success">{{ __('general_content.external_id_trans_key') }}</label>  {{  $Purchase->code }}</p>
                    <p><label for="date" class="text-success">{{ __('general_content.date_trans_key') }}</label>  {{  $Purchase->GetshortCreatedAttribute() }}</p>
                  </div>
                  <div class="form-group col-md-6">
                    @include('include.form.form-input-label',['label' =>__('general_content.name_purchase_trans_key'), 'Value' =>  $Purchase->label])
                  </div>
                </div>
                <div class="row">
                  <label for="InputWebSite">{{ __('general_content.supplier_info_trans_key') }}</label>
                </div>
                @if( $Purchase->companies_contacts_id == 0 & $Purchase->companies_addresses_id ==0)
                <x-adminlte-alert theme="info" title="Info">{{  __('general_content.update_valide_trans_key') }}</x-adminlte-alert>
                @endif
                <div class="row">
                  <div class="form-group col-md-6">
                    @include('include.form.form-select-companie',['companiesId' =>  $Purchase->companies_id])
                  </div>
                  <div class="form-group col-md-6">
                    
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    @include('include.form.form-select-adress',['adressId' =>   $Purchase->companies_addresses_id])
                  </div>
                  <div class="form-group col-md-6">
                    @include('include.form.form-select-contact',['contactId' =>   $Purchase->companies_contacts_id])
                  </div>
                </div>
                <div class="row">
                  <x-FormTextareaComment  comment="{{ $Purchase->comment }}" />
                </div>
                <x-slot name="footerSlot">
                  <x-adminlte-button class="btn-flat" type="submit" label="{{ __('general_content.update_trans_key') }}" theme="info" icon="fas fa-lg fa-save"/>
                </x-slot>
              </x-adminlte-card>
            </form>
          </div>
          <div class="col-md-3">
            
            <x-adminlte-card title="{{ __('general_content.informations_trans_key') }}" theme="secondary" maximizable>
              @include('include.sub-total-price')
            </x-adminlte-card>

            <x-adminlte-card title="{{ __('general_content.options_trans_key') }}" theme="warning" maximizable>
              <div class="table-responsive p-0">
                <table class="table table-hover">
                  <tr>
                      <td style="width:50%"> 
                        {{ __('general_content.purchase_trans_key') }}
                      </td>
                      <td>
                        @if( $Purchase->companies_contacts_id != 0 & $Purchase->companies_addresses_id !=0)
                        <x-ButtonTextPDF route="{{ route('pdf.purchase', ['Document' => $Purchase->id])}}" />
                        @else
                        {{  __('general_content.update_valide_trans_key') }}
                        @endif
                      </td>
                  </tr>
                </table>
              </div>
              <hr>
              <div class="card-body">
                @if($Purchase->Rating->isEmpty())
                <form action="{{ route('companies.ratings.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="purchases_id" value="{{ $Purchase->id }}" >
                  <input type="hidden" name="companies_id" value="{{ $Purchase->companies_id }}" >
                  <div class="form-group">
                    <label for="rating">{{ __('general_content.supplier_rate_trans_key') }}</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-star-half-alt"></i></span>
                      </div>
                      <select name="rating" id="rating" class="form-control">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <x-FormTextareaComment  comment="" />
                  </div>
                  <x-adminlte-button class="btn-flat" type="submit" label="{{ __('general_content.submit_trans_key') }}" theme="danger" icon="fas fa-lg fa-save"/>
                </form>
                @else
                  @php
                      $Rating = $Purchase->Rating->toArray();
                  @endphp
                  <label for="rating">{{ __('general_content.supplier_rate_trans_key') }}</label>
                  @for ($i = 1; $i <= 5; $i++)
                      @if ($i <= $Rating[0]['rating'])
                          <span class="badge badge-warning">&#9733;</span>
                      @else
                          <span class="badge badge-info">&#9734;</span>
                      @endif
                  @endfor
                @endif
              </div>
            </x-adminlte-card>

            @include('include.file-store', ['inputName' => "purchases_id",'inputValue' => $Purchase->id,'filesList' => $Purchase->files,])
          </div>
        </div>
      </div>    
      <div class="tab-pane " id="PurchaseLines">
          @livewire('purchases-lines-index', ['purchase_id' => $Purchase->id, 'OrderStatu' => $Purchase->statu])
      </div>
      @if($CustomFields)
      <div class="tab-pane " id="CustomFields">
        @include('include.custom-fields-form', ['id' => $Purchase->id, 'type' => 'purchase'])
      </div>
      @endif
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
@stop

@section('css')
@stop

@section('js')
@stop