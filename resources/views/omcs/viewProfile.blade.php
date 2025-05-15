@extends('home')

@section('content')

<div class="back-button">
    <a href="{{ route('overviewOMC') }}" 
       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
       <i class="fa-solid fa-arrow-left"></i> Back to OMC's Overview
    </a>
</div>

<h2 class="main-title">Account Detail</h2>

<div id="deleteModal" class="delete-modal fixed inset-0 hidden">
    <div class="delete-mod rounded-md">
        <h2 class="font-semibold mb-4">Confirm Deletion</h2>
        <p>Are you sure you want to delete this OMC?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="delete-modal__button">
                <button type="submit" class="confirm-button">Delete</button>
                <button type="button" onclick="closeModal()" class="cancel-button">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="suspendModal" class="delete-modal fixed inset-0 hidden">
    <div class="delete-mod rounded-md">
        <h2 class="font-semibold mb-4">Confirm Suspension</h2>
        <p>Are you sure you want to suspend this account?</p>
        <form id="suspendForm" method="POST">
            @csrf
            <div class="delete-modal__button">
                <button type="submit" class="confirm-button">Suspend</button>
                <button type="button" onclick="closeSuspendModal()" class="cancel-button">Cancel</button>
            </div>
        </form>
    </div>
</div>


<div class="visa-view w-full px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker">
    
    <div class="view-row">
        <div class="view-label">OMC Name</div>
        <div class="view-data">
          <div>{{ old('omcName', $omc->omcName) }}</div>
        </div>
    </div>
  
    <div class="view-row">
      <div class="view-label">Photo of the OMC</div>
      <div class="view-data">
        <div class="photo-placeholder">
            <picture>
              <source srcset="{{ Vite::asset('resources/img/avatar/avatar-illustrated-02.webp') }}" type="image/webp">
              <img src="{{ Vite::asset('resources/img/avatar/avatar-illustrated-02.png') }}" alt="{{ old('omcName', $omc->omcName) }}">
            </picture>
          </div>
      </div>
    </div>

    <div class="view-row">
        <div class="view-label">E-Mail</div>
        <div class="view-data">
          <div>{{ old('email', $omc->email) }}</div>
        </div>
    </div>

    <div class="view-row">
        <div class="view-label">Phone No.</div>
        <div class="view-data">
          <div>{{ old('phone', $omc->phone) }}</div>
        </div>
    </div>

    <div class="view-row">
        <div class="view-label">License No.</div>
        <div class="view-data">
          <div>{{ old('licence_no', $omc->licence_no) }}</div>
        </div>
    </div>

    <div class="view-row">
        <div class="view-label">Address</div>
        <div class="view-data">
          <div>{{ old('address', $omc->address) }}</div>
        </div>
    </div>

    <div class="view-row">
        <div class="view-label">Total Dealers</div>
        <div class="view-data">
            <div>
                <a href="{{ route('viewDealer', $omc->id) }}" 
                   class="text-blue-600 hover:text-blue-800 hover:underline">
                    {{ $dealerCount }}
                </a>
            </div>
        </div>
    </div>

    <div class="view-row">
        <div class="view-label">Created</div>
        <div class="view-data">
          <div>{{ old('created_at', $omc->created_at) }}</div>
        </div>
    </div>

</div>

<div class="acc">
    
    <div class="deactivation-container w-full px-4 py-6">
        <h1>
            @if($omc->status === 'pending')
                Approve Account
            @elseif($omc->status === 'active')
                Suspend Account
            @elseif($omc->status === 'suspended')
                Activate Account
            @endif
        </h1>
        
        <div class="confirmation-box">
            <label class="checkbox-container">
                <input type="checkbox" id="confirm-suspend">
                <span class="checkmark-suspend"></span>
                <span class="label-text">
                    @if($omc->status === 'pending')
                        I confirm this account approval
                    @elseif($omc->status === 'active')
                        I confirm this account suspension
                    @elseif($omc->status === 'suspended')
                        I confirm this account activation
                    @endif
                </span>
            </label>
        </div>
        
        <button class="suspend-button" disabled id="suspend-btn">
            <h4>
                @if($omc->status === 'pending')
                    Approve Account
                @elseif($omc->status === 'active')
                    Suspend Account
                @elseif($omc->status === 'suspended')
                    Activate Account
                @endif
            </h4>
        </button>
    </div>
    
    <div class="deactivation-container w-full px-4 py-6 sec">

        <h1>Delete Account</h1>
    
        <div class="confirmation-box">
            <label class="checkbox-container">
                <input type="checkbox" id="confirm-deactivation">
                <span class="checkmark"></span>
                <span class="label-text">I confirm this account deletion</span>
            </label>
        </div>

        <button type="button" id="open-modal-btn" onclick="openModal('{{ route('deleteOMC', $omc->id) }}')" class="deactivate-button" disabled>Delete Account</button>

    </div>
    

</div>

  <script>
    
    document.getElementById("confirm-deactivation").addEventListener("change", function () {
        document.getElementById("open-modal-btn").disabled = !this.checked;
    });

    document.getElementById("confirm-suspend").addEventListener("change", function() {
        document.getElementById("suspend-btn").disabled = !this.checked;
    });

    document.getElementById("suspend-btn").addEventListener("click", function() {
        const currentStatus = "{{ $omc->status }}";
        const actionUrl = "{{ route('suspendOMC', $omc->id) }}";
        openSuspendModal(actionUrl, currentStatus);
    });

    function openModal(actionUrl) {
        const modal = document.getElementById("deleteModal");
        modal.classList.add("show"); 
        document.getElementById("deleteForm").setAttribute("action", actionUrl);
    }

    function closeModal() {
        const modal = document.getElementById("deleteModal");
        modal.classList.remove("show");
    }

    function openSuspendModal(actionUrl, currentStatus) {
        const modal = document.getElementById("suspendModal");
        modal.classList.add("show");
        const form = document.getElementById("suspendForm");
        form.setAttribute("action", actionUrl);
    
        const confirmButton = form.querySelector(".confirm-button");
        const statusInput = document.getElementById("newStatus");
    
        if (currentStatus === 'pending') {
            document.querySelector("#suspendModal h2").textContent = "Confirm Approval";
            document.querySelector("#suspendModal p").textContent = "Are you sure you want to approve this account?";
            confirmButton.textContent = "Approve";
            statusInput.value = 'active';
        } else if (currentStatus === 'active') {
            document.querySelector("#suspendModal h2").textContent = "Confirm Suspension";
            document.querySelector("#suspendModal p").textContent = "Are you sure you want to suspend this account?";
            confirmButton.textContent = "Suspend";
            statusInput.value = 'suspended';
        } else if (currentStatus === 'suspended') {
            document.querySelector("#suspendModal h2").textContent = "Confirm Activation";
            document.querySelector("#suspendModal p").textContent = "Are you sure you want to activate this account?";
            confirmButton.textContent = "Activate";
            statusInput.value = 'active';
        }
    }

    function closeSuspendModal() {
        const modal = document.getElementById("suspendModal");
        modal.classList.remove("show");
    }

  </script>

@endsection

