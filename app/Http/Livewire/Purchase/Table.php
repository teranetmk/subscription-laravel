<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Table extends Component
{
    use WithPagination;

    public $search;
    public $user_id;
    protected $queryString = ['search'];

    public function mount()
    {
        $this->search = '';
        $this->user_id = currentUser()->id;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $purchases = DB::table('purchases')
        ->where('purchases.user_id', $this->user_id)
        ->where('purchases.amount','like','%'.ucfirst($this->search).'%')
        ->orderBy('purchases.created_at', 'desc');

        return view('livewire.purchase.table', [
            'purchases' => $purchases->select('*')->simplePaginate(10),
        ]);
    }

}
