<?php
/**
 * My Info Page (user/finance)
 *
 * @author  - Ri Chol Min
 */
?>
@extends('layouts/freelancer/user')

@section('content')
<div class="title-section">
  <span class="title">Finance</span>
  <div class="right-action-link">
    <a href="#">Edit</a>
  </div>
</div>
<div class="page-content-section freelancer-user-page">
  <div class="info-section form-horizontal">
    
  </div>

  <div class="form-section">
    <form id="RegisterForm" class="form-horizontal" method="post" action="/user/finance">      
    </form>
  </div>
</div>
@endsection