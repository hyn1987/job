<?php
/**
 * My Info Page (job/search)
 *
 * @author  - nada
 */
?>
@extends('layouts/freelancer/index')

@section('content')
<div class="title-section">
	<div class="row">
		<div class="col-sm-3">
			<span class="title">{{ trans('page.' . $page . '.title') }}</span>
		</div>
	</div>  
</div>
{{ show_messages() }}
<div class="page-content-section freelancer-page">
	<form class="" action="{{ route('admin.user.update') }}" method="post" role="form">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<div class="input-group">
						<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>	
						<input class="form-control" type="text" placeholder="Search for Jobs..."/>
							
						<span class="input-group-btn">
							<button class="btn btn-primary" type="submit">Search</button>						
						</span>					
					</div>
					<p class="help-block">Advanced Search</p>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-3">
				<form role="">
					<div class="left-side-box">
						<div class="box-header">
							Category
						</div>
						<div class="box-content">
							<select class="form-control" name="op_category">
				                <option value="">OP1</option>
				                <option value="">OP2</option>
				                <option value="">OP3</option>
				            </select> 
						</div>	
					</div>	

					<div class="left-side-box">
						<div class="box-header">
							Job Type
						</div>
						<div class="box-content">
							<div class="checkbox-list">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hourly"> Hourly (43,756)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> Fixed Price (52,353)
									</label>								
								</div>
							</div>
						</div>	
					</div>	

					<div class="left-side-box">
						<div class="box-header">
							Experience Level
						</div>
						<div class="box-content">
							<div class="checkbox-list">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hourly"> Entry Level - $ (24,498)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> Intermediate - $$ (43,902)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> Expert - $$$ (17,638)
									</label>								
								</div>
							</div>
						</div>	
					</div>

					<div class="left-side-box">
						<div class="box-header">
							Client History
						</div>
						<div class="box-content">
							<div class="checkbox-list">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hourly"> No Hires (32,156)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> 1 to 9 Hours (29,169)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> 10+ Hours (34,784)
									</label>								
								</div>
							</div>
						</div>	
					</div>

					<div class="left-side-box">
						<div class="box-header">
							Location
						</div>
						<div class="box-content">
							<select class="form-control" name="op_category">
				                <option value="">OP1</option>
				                <option value="">OP2</option>
				                <option value="">OP3</option>
				            </select> 
						</div>	
					</div>

					<div class="left-side-box">
						<div class="box-header">
							Budget
						</div>
						<div class="box-content">
							
						</div>	
					</div>

					<div class="left-side-box">
						<div class="box-header">
							Project Length
						</div>
						<div class="box-content">
							<div class="checkbox-list">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hours_or_days"> Hours or Days(32,156)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> Weeks (29,169)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> Months (34,784)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> > 6 Months (29,169)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> Not Specified (0)
									</label>								
								</div>
							</div>
						</div>	
					</div>

					<div class="left-side-box">
						<div class="box-header">
							Hours per Week
						</div>
						<div class="box-content">
							<div class="checkbox-list">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hourly"> Part Time (32,156)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> Full Time (29,169)
									</label>								
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="fixed_price"> Not Specified (34,784)
									</label>								
								</div>
							</div>
						</div>	
					</div>

				</form>

			</div>

			<div class="col-sm-9">
				<div class="result-side">

					<div class="content-box">	
						<div class="header">
							<div class="row">
								<div class="col-sm-6 form-inline">
									<div class="form-group">						
										<label>Sort by:</label>
										<select class="form-control" name="op_category">
								                <option value="">OP1</option>
								                <option value="">OP2</option>
								                <option value="">OP3</option>
								        </select>
								    </div>
							    </div>
							    <div class="col-sm-6 form-inline">						
									<label>Sort by:</label>
									<select class="form-control" name="op_category">
							                <option value="">OP1</option>
							                <option value="">OP2</option>
							                <option value="">OP3</option>
							        </select>
							    </div>
							</div>
						</div>
					</div>
					
					<div class="content-box">							
						<div class="header">
							<div class="row title">
								<div class="col-sm-9">
									Create a Facebook Page for Our Website
								</div>
								<div class="col-sm-3 align-right ">
									<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
									<span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span>
								</div>
							</div>
						</div>
						<div class="summary">
							Hourly - Entry level ($) - Est. Time : Less than a week, 10 ~ 30 hrs/week  - Posted 2 minutes ago
						</div>
						<div class="desc">
							Now normally you don’t want to use the get() method, as its an easy way to populate your
							input array with extra data you don’t need. In fact the open source collaboration site github was
							a victim to mass assignment. I have used get() to simplify the tutorial. In your applications
							please build the input array only with the fields you need.
						</div>
						<div class="xxx">
							Clients: 
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
							skills:
							<span class="label label-default">Javascript</span>
							<span class="label label-default">PHP</span>
							<span class="label label-default">Ruby On Rails</span>
						</div>
					</div>
					
					
				</div>
			</div>
		</div>
	</form>
</div>
@endsection