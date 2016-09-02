<?php
/**
   * Show all freelancers.
   *
   * @author Jin
   * @since Jan 22, 2016
   * @version 1.0 Show all freelancers.
   * @return Response
   */
?>
@extends('layouts/freelancer/index')

@section('content')

  <div class="page-header">
    <div class="row">
      <div class="col-md-12">
          <h3>{{ trans('page.' . $page . '.title') }}</h3>
      </div>
    </div>
  </div>

  <div class="page-content-section freelancer-user-page"> 
          
    <div class="row">
      <div class="col-md-12">
        <form id="form_user_search" class="" method="post" action="{{ route('user.search')}}">
          <div class="form-group">
            <div class="input-group">
              <div class="input-cont">
                <input class="form-control" type="text" placeholder="Search...">
              </div>
              <span class="input-group-btn">
                <button class="btn h_color" type="button">
                  Search &nbsp;
                <i class=""></i>
                </button>
              </span>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12"> 

      <div class="search_section">

        <div class="row">   
          <div class="col-md-3">
            <select class="form-control" name="category">
                <option value="">CT1</option>
                <option value="">CT2</option>
                <option value="">CT3</option>
            </select> 
          </div>
          <div class="col-md-3">
            <select class="form-control" name="stars">
                <option value="">ST1</option>
                <option value="">ST2</option>
                <option value="">ST3</option>
            </select> 
          </div>
          <div class="col-md-2">
            <select class="form-control" name="experience">
                <option value="">EXP1</option>
                <option value="">EXP2</option>
                <option value="">EXP3</option>
            </select> 
          </div>
          <div class="col-md-2">
            <select class="form-control" name="options">
                <option value="">OP1</option>
                <option value="">OP2</option>
                <option value="">OP3</option>
            </select> 
          </div> 
          <div class="col-md-2">
            <div class="form-control no_border">
                <a class="h_color cursor_pointer">Clear All Filters</a>
            </div>
          </div> 
        </div> 

        <div class="row">
          <div class="col-md-12">
            <div class="pull-right">
              {!! $users->render() !!}
            </div>
            <div style="clear:both;"></div>
          </div>
        </div>

        @foreach ($users as $id => $user)
        <div class="row">
          <div class="col-md-12 col_md_12_margin_top_10px">    
            <div class="row">

              <div class="col-md-3">
                <img alt="" class="img-circle hide1" src="{{ $user->contact->avatar }}" width="120" height="120"> 
              </div> 

              <div class="col-md-9 col_md_9_border_bottom_1px">
                <div class="row">

                  <div class="col-md-9"> 
                    <h4 class="h_color">
                      <b>{{ $user->contact->first_name . " " . $user->contact->last_name }}</b>
                    </h4>
                    <h4>{{ $user->contact->country ? $user->contact->country->name : '' }} </h4> 
                    <h4>
                      <b>Ruby On Rails / Javascript / Angular Developer, Team Lead</b>
                    </h4>
                    <p>Ukline - last active: 2 days ago -tests 
                      <span class="h_color">7</span> - Portfolio:
                      <span class="h_color">10</span>
                    </p>
                    
                    <p>This Fifth Edition is completely revised and expanded to cover JavaScript as it is used in today's Web 2.0 applications. This book is both an example-driven programmer's guide and a keep-on-your-desk reference, with new chapters that explain everything you need to know to get the most out of JavaScript, including:
                    </p>
                    <p>
                      <span class="label label-default">Ruby&nbsp;&nbsp;
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                      </span>&nbsp;&nbsp;
                      <span class="label label-default">Ruby On Rails&nbsp;&nbsp;
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                      </span>&nbsp;&nbsp;
                      <span class="label label-default">Javascript&nbsp;&nbsp;
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                      </span>&nbsp;&nbsp;
                      <span class="label label-default">Php&nbsp;&nbsp;
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                      </span>&nbsp;&nbsp;
                      <span class="h_color">4 more</span>
                    </p>
                  </div>

                  <div class="col-md-3">  
                    <p class="text-right h_color">Top Related</p>
                    <p class="text-right">$38.00 / hr</p>
                    <p class="text-right">648 hours</p>
                    <p class="text-right">90% Job Success</p>
                    <p class="text-right">5.00 
                      <span class="glyphicon glyphicon-star h_color" aria-hidden="true"></span>
                      <span class="glyphicon glyphicon-star h_color" aria-hidden="true"></span>
                      <span class="glyphicon glyphicon-star h_color" aria-hidden="true"></span>
                      <span class="glyphicon glyphicon-star h_color" aria-hidden="true"></span>
                      <span class="glyphicon glyphicon-star h_color" aria-hidden="true"></span>          
                    </p>
                  </div>

                </div>
              </div>
              
            </div>    
          </div>
        </div>
        @endforeach

        <div class="row">
          <div class="col-md-12">
            <div class="pull-right">
              {!! $users->render() !!}
            </div>
            <div style="clear:both;"></div>
          </div>
        </div>

        <div class="row row_border_top_1px">
          <div class="col-md-12">
            <p class="h_color">
                This Fifth Edition is completely revised and expanded to cover JavaScript as it is used in today's Web 2.0 applications. This book is both an example-driven programmer's guide and a keep-on-your-desk reference, with new chapters that explain everything you need to know to get the most out of JavaScript, including:
              </p>
          </div>    
        </div>

        </div>  

      </div>
    </div>  
     
</div>

@endsection