@php
  $labelMain = __("translation.Loan Terms");
  $label = __("translation.Loan Terms");
@endphp
@extends('layouts.main')
@section('title', $labelMain)
@push('css')
    <link href="{{asset('/')}}backend/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="row mt-3 mb-3">
    <div class="col-md-6">
        <h4 class="page-title">{{ $labelMain }}</h4>
    </div>
    <div class="col-md-6">
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        @php
                            $loanAmount = !empty($loanData->amount)?$loanData->amount:0;
                            $user = isset($_GET['uid']) ? \App\Models\User::find($_GET['uid']) : auth()->user();
                        @endphp
                        <h4>Name : {{ $user->first_name." ".$user->last_name }}</h4>
                        @if(isset($_GET['uid']) && isset($_GET['lid']))
                            <h5>{{ __("translation.Debit As of Today") }} : {{ currencyFormatter(0) }}</h5>
                            <h5>{{ __("translation.Credit Available") }} : {{ currencyFormatter($user->authorized_credit_limit) }}</h5>
                        @else
                            <h5>{{ __("translation.Debit As of Today") }} : {{ currencyFormatter($loanAmount) }}</h5>
                            <h5>{{ __("translation.Credit Available") }} : {{ currencyFormatter($user->authorized_credit_limit - $loanAmount) }}</h5>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("translation.Installment Date") }}</th>
                                    <th>{{ __("translation.Capital") }}</th>
                                    <th>{{ __("translation.Interest") }}</th>
                                    <th>{{ __("translation.Installment Amount") }}</th>
                                    <th>{{ __("translation.Balance") }}</th>
                                    <th>{{ __("translation.Status") }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(count($loanInstallments) > 0){
                                            $i = 0;
                                            echo "<tr>";
                                            echo "<td>".$i."</td>";
                                            echo "<td>" . \Carbon\Carbon::parse($loanData->first_installment_date)->subWeek()->format('d-m-Y') . "</td>";
                                            echo "<td></td>";
                                            echo "<td></td>";
                                            echo "<td></td>";
                                            echo "<td>".currencyFormatter($totalLoanAmountAsOfToday)."</td>";
                                            echo "<td>".$loanStatusName."</td>";
                                            echo "</tr>";
                                            $totalCapital = 0;
                                            $totalInterest = 0;
                                            $totalPayment = 0;
                                            foreach ($loanInstallments as $installment) {
                                                $i++;
                                                echo "<tr>";
                                                echo "<td>" . $i . "</td>";
                                                echo "<td>" . date('d-m-Y', strtotime($installment->installment_date)) . "</td>";
                                                echo "<td>" . currencyFormatter($installment->capital) . "</td>";
                                                echo "<td>" . currencyFormatter($installment->interest) . "</td>";
                                                echo "<td>" . currencyFormatter($installment->payment) . "</td>";
                                                echo "<td>" . currencyFormatter($installment->balance) . "</td>";
                                                $installmentStatus = ($installment->status ==  1) ? "Pending" : "Paid";
                                                echo "<td>" . $installmentStatus . "</td>";
                                                echo "</tr>";
                                                $totalCapital += $installment->capital;
                                                $totalInterest += $installment->interest;
                                                $totalPayment += $installment->payment;
                                            }
                                            echo "<tr>";
                                            echo "<td colspan=2></td>";
                                            echo "<td>".currencyFormatter(round($totalLoanAmountAsOfToday))."</td>";
                                            echo "<td>".currencyFormatter($totalInterest)."</td>";
                                            echo "<td>".currencyFormatter($totalPayment)."</td>";
                                            echo "<td colspan=2></td>";
                                            echo "</tr>";
                                        }else{
                                            echo "<tr>";
                                            echo "<td colspan=2></td>";
                                            echo "<td></td>";
                                            echo "<td>No records found.</td>";
                                            echo "<td></td>";
                                            echo "<td colspan=2></td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
