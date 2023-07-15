@extends('admin.admin_master')

@section('content')
<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
                    <p class="alert-success">
                        <?php
                        $message = Session::get('message');
                        if($message){
                            echo $message;
                            Session::put('message',null);
                        }
                        ?>
                    </p>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div class="span9"></div>
							<div class="span3">
								<span class="btn btn-danger">
									<a href="{{url('/categories-trash')}}">View Trash</a>
								</span>
							</div>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th style="width:5%;">Id</th>
								  <th style="width:15%;">Category_Name</th>
								  <th style="width:30%;">Description</th>
                                  <th style="width:15%;">image</th>
								  <th style="width:15%;">Status</th>
								  <th style="width:20%;">Actions</th>
							  </tr>
						  </thead>
                          @foreach($categories as $category)   
						  <tbody>
							<tr>
								<td>{{$category->id}}</td>
								<td class="center">{{$category->name}}</td>
								<td class="center">{!!$category->description!!}</td>
								<td class="center"><img src="{{ asset('images/' . $category->created_at->format('Y/m/d') . '/' . $category->image) }}" alt="" style="width:150px;height:80px;"></td>
                                
								<td class="center">
                                    @if($category->status==1)
									<span class="label label-success">Active</span>
                                    @else
                                    <span class="label label-danger">Deactive</span>
                                    @endif
								</td>
								<td class="row">
                                    <div class="span3"></div>
                                    <div class="span2">
                                    @if($category->status==1)
									<a class="btn btn-success" href="{{url('/cat-status'.$category->id)}}">
										<i class="halflings-icon white thumbs-down"></i>  
									</a>
                                    @else
                                    <a class="btn btn-danger" href="{{url('/cat-status'.$category->id)}}">
										<i class="halflings-icon white thumbs-up"></i>  
									</a>
                                    @endif
                                    </div>
                                    <div class="span2">
									<a class="btn btn-info" href="{{url('categories/'.$category->id.'/edit')}}">
										<i class="halflings-icon white edit"></i>  
									</a>
                                    </div>
                                    <div class="span2">
									<form action="{{url('/categories/'.$category->id)}}" method="post">
										@csrf
										@method('DELETE')
									<button class="btn btn-danger" type="submit" value="Trash">
										<i class="halflings-icon white trash"></i> 
									</button>
									</form>
                                    </div>
                                    <div class="span3"></div>
								</td>
							</tr>
							
							
						  </tbody>
                          @endforeach
					  </table>            
					</div>
				</div><!--/span-->
			
			</div>
@endsection