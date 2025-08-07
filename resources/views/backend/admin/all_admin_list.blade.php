@extends('admin.admin_master')
@section('title', 'Admin Management')
@section('admin')
<div class="card bg-white border-0 rounded-3 mb-4">
   <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
         <h3 class="mb-0">Admin Management</h3>
         <div class="d-flex gap-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <i class="material-symbols-outlined fs-16">add</i> Add Admin
            </button>
         </div>
      </div>
      <div class="default-table-area all-products">
         <div class="table-responsive">
            <table class="table align-middle" id="myTable">
               <thead>
                  <tr>
                     <th scope="col">Admin ID</th>
                     <th scope="col">Admin</th>
                     <th scope="col">Email</th>
                     <th scope="col">Address</th>
                     <th scope="col">Phone</th>
                     <th scope="col">Role</th>
                     <th scope="col">Join Date</th>
                     <th scope="col">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($admins as $admin)
                  <tr>
                     <td class="text-body">#ADM-{{ str_pad($admin->id, 3, '0', STR_PAD_LEFT) }}</td>
                     <td>
                        <div class="d-flex align-items-center">
                           @if($admin->image)
                           <img src="{{ asset('upload/admin/'.$admin->image) }}" class="wh-40 rounded-3" alt="admin">
                           @else
                           <div class="wh-40 rounded-3 bg-light d-flex align-items-center justify-content-center">
                              {{ substr($admin->name, 0, 1) }}
                           </div>
                           @endif
                           <div class="ms-2 ps-1">
                              <h6 class="fw-medium fs-14 mb-0">{{ $admin->name }}</h6>
                           </div>
                        </div>
                     </td>
                     <td class="text-secondary">{{ $admin->email }}</td>
                     <td class="text-secondary">{{ $admin->address }}</td>
                     <td class="text-secondary">{{ $admin->phone }}</td>
                     <td class="text-secondary">
                        @foreach($admin->roles as $role)
                        <span class="badge bg-success">{{ $role->name }}</span>
                        @endforeach
                     </td>
                     <td class="text-secondary">{{ $admin->formatted_created_at }}</td>
                     <td>
                        <div class="d-flex align-items-center gap-1">
                           <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" 
                              data-bs-toggle="modal" 
                              data-bs-target="#editAdminModal{{ $admin->id }}"
                              data-bs-placement="top" 
                              data-bs-title="Edit">
                           <i class="material-symbols-outlined fs-16 text-body">edit</i>
                           </button>
                           <a href="{{ route('admin.delete', $admin->id) }}" 
                              class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" 
                              id="delete"
                              data-bs-toggle="tooltip" 
                              data-bs-placement="top" 
                              data-bs-title="Delete">
                           <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                           </a>
                        </div>
                     </td>
                  </tr>
                  <!-- Edit Admin Modal -->
                  <div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                              <h5 class="modal-title">Edit Admin</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                           </div>
                           <form action="{{ route('admin.update', $admin->id) }}" method="POST">
                              @csrf
                              <div class="modal-body">
                                 <div class="row g-3">
                                    <div class="col-md-6">
                                       <label class="form-label">Name <span class="text-danger">*</span></label>
                                       <input type="text" class="form-control" name="name" value="{{ $admin->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                       <label class="form-label">Email <span class="text-danger">*</span></label>
                                       <input type="email" class="form-control" name="email" value="{{ $admin->email }}" required>
                                    </div>
                                    <div class="col-md-6">
                                       <label class="form-label">Phone <span class="text-danger">*</span></label>
                                       <input type="text" class="form-control" name="phone" value="{{ $admin->phone }}" required>
                                    </div>
                                    <div class="col-md-6">
                                       <label class="form-label">Role <span class="text-danger">*</span></label>
                                       <select class="form-select" name="roles" required>
                                       @foreach($roles as $role)
                                       <option value="{{ $role->id }}" {{ $admin->roles->contains($role->id) ? 'selected' : '' }}>
                                       {{ $role->name }}
                                       </option>
                                       @endforeach
                                       </select>
                                    </div>
                                    <div class="col-md-6">
                                       <label class="col-form-label">Gender <span class="text-danger">*</span></label>
                                       <select class="form-select" name="gender" required>
                                          <option value="" disabled>-- Select --</option>
                                          <option value="male" {{ $admin->gender === 'male' ? 'selected' : '' }}>Male</option>
                                          <option value="female" {{ $admin->gender === 'female' ? 'selected' : '' }}>Female</option>
                                       </select>
                                       <small class="form-control-feedback">
                                       @error('gender')
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                       </small>
                                    </div>
                                    <div class="col-md-6">
                                       <label class="form-label">Password</label>
                                       <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                                    </div>
                                    <div class="col-12">
                                       <label class="form-label">Address <span class="text-danger">*</span></label>
                                       <textarea class="form-control" name="address" rows="3" required>{{ $admin->address }}</textarea>
                                    </div>
                                 </div>
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                 <button type="submit" class="btn btn-primary">Save Changes</button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Add New Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="{{ route('admin.store') }}" method="POST">
            @csrf
            <div class="modal-body">
               <div class="row g-3">
                  <div class="col-md-6">
                     <label class="form-label">Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" name="name" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Email <span class="text-danger">*</span></label>
                     <input type="email" class="form-control" name="email" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Phone <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" name="phone" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Role <span class="text-danger">*</span></label>
                     <select class="form-select" name="roles" required>
                        <option value="" selected disabled>Select Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6">
                     <label class="col-form-label">Gender <span class="text-danger">*</span></label>
                     <select class="form-select" name="gender" required>
                        <option value="" selected disabled>-- Select --</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                     </select>
                     <small class="form-control-feedback">
                     @error('gender')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                     </small>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Password <span class="text-danger">*</span></label>
                     <input type="password" class="form-control" name="password" required>
                  </div>
                  <div class="col-12">
                     <label class="form-label">Address <span class="text-danger">*</span></label>
                     <textarea class="form-control" name="address" rows="3" required></textarea>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Create Admin</button>
            </div>
         </form>
      </div>
   </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
// Initialize DataTable
$('#myTable').DataTable();
// Initialize Tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
return new bootstrap.Tooltip(tooltipTriggerEl)
});
// Delete Confirmation
$(document).on('click', '#delete', function(e) {
e.preventDefault();
var link = $(this).attr("href");
Swal.fire({
title: 'Are you sure?',
text: "You won't be able to revert this!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Yes, delete it!'
}).then((result) => {
if (result.isConfirmed) {
window.location.href = link;
}
});
});
});
@endpush
@endsection
