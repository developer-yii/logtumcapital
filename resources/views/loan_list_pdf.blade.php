<!DOCTYPE html>
<html>
<head>
    <title>{{ __("translation.Loan List PDF") }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 5px; /* Decrease padding around the page */
        }
        h1 {
            text-align: center;
            font-size: 20px; /* Decrease font size for the title */
            margin-bottom: 15px; /* Adjust margin for the title */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px; /* Adjust bottom margin for the table */
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 3px; /* Decrease padding in table cells */
        }
        th {
            background-color: #f2f2f2;
            font-size: 14px; /* Decrease font size for table headers */
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            font-size: 14px; /* Decrease font size for summary */
            font-weight: bold;
            white-space: nowrap; /* Prevent text wrapping */
        }
        .summary span {
            margin-right: 15px; /* Adjust margin between summary items */
        }
        footer {
            margin-top: 15px; /* Adjust top margin for the footer */
            font-size: 12px; /* Decrease font size for footer */
            white-space: nowrap; /* Prevent text wrapping */
        }
        .date {
            font-size: 14px; /* Decrease font size for dates in table */
        }
        footer {
            font-size: 12px;
            white-space: nowrap;
            margin-top: auto; /* Push footer to the bottom */
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>{{ $title }}</h1>
    </header>
    <table>
        <thead>
            <tr>
                <th>{{ __("translation.Loan") }}</th>
                <th>{{ __("translation.Weeks") }}</th>
                <th>{{ __("translation.Name") }}</th>
                <th>{{ __("translation.Payment") }}</th>
                <th>{{ __("translation.Delivery") }}</th>
                <th>{{ __("translation.Start Date") }}</th>
                <th>{{ __("translation.End Date") }}</th>
                <th>{{ __("translation.Company") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loanData as $loan)
                <tr>
                    <td>{{ currencyFormatter($loan->amount) }}</td>
                    <td>{{ $loan->duration }}</td>
                    <td>{{ $loan->employee_name }}</td>
                    <td>{{ currencyFormatter($loan->payment) }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($loan->disbursed_date)->format('M d, Y') }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($loan->first_installment_date)->format('M d, Y') }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($loan->last_installment_date)->format('M d, Y') }}</td>
                    <td>{{ $loan->company_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="summary">
        <span>{{ __("translation.Total Loan Amount") }} : {{ currencyFormatter($totalLoanAmount) }}</span>
        <span>{{ __("translation.Total Loans") }} : {{ $totalLoans }}</span>
        <span>{{ __("translation.Total Payment") }} : {{ currencyFormatter($totalPayment) }}</span>
    </div>
    <footer>
        <p>{{ __("translation.Downloaded as on") }} : {{ \Carbon\Carbon::now()->format('M d, Y H:i:s') }}</p>
    </footer>
</body>
</html>
