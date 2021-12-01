<?php

namespace Crater\Http\Controllers\V1\Invoice;

use Auth;
use Crater\Http\Controllers\Controller;
use Crater\Http\Requests\DeleteInvoiceRequest;
use Crater\Http\Requests\InvoicesRequest;
use Crater\Jobs\GenerateInvoicePdfJob;
use Crater\Models\Invoice;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $limit = $request->has('limit') ? $request->limit : 10;

        $invoices = Invoice::with(['items', 'user', 'creator', 'taxes'])
            ->join('users', 'users.id', '=', 'invoices.user_id')
            ->applyFilters($request->only([
                'status',
                'paid_status',
                'customer_id',
                'invoice_id',
                'invoice_number',
                'from_date',
                'to_date',
                'orderByField',
                'orderBy',
                'search',
            ]))
            ->whereCompany($request->header('company'));

        if ($user->isCustomer()) {
            $invoices = $invoices->where('user_id', $user->id);
        }

        $invoices = $invoices->select('invoices.*', 'users.name')
            ->latest()
            ->paginateData($limit);

        return response()->json([
            'invoices' => $invoices,
            'invoiceTotalCount' => Invoice::count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(InvoicesRequest $request)
    {

        $user = Auth::user();

        if ($user->isCustomer()) {
            return response('Unauthorized.', 401);
        }

        $invoice = Invoice::createInvoice($request);

        if ($request->has('invoiceSend')) {
            $invoice->send($request->subject, $request->body);
        }

        GenerateInvoicePdfJob::dispatch($invoice);

        return response()->json([
            'invoice' => $invoice,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Crater\Models\Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Invoice $invoice)
    {
        $user = Auth::user();

        if ($user->isCustomer() && $invoice->user_id !== $user->id) {
            return response('Unauthorized.', 401);
        }

        $invoice->load([
            'items',
            'items.taxes',
            'user',
            'taxes.taxType',
            'fields.customField',
        ]);

        $siteData = [
            'invoice' => $invoice,
            'nextInvoiceNumber' => $invoice->getInvoiceNumAttribute(),
            'invoicePrefix' => $invoice->getInvoicePrefixAttribute(),
        ];

        return response()->json($siteData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InvoicesRequest $request, Invoice $invoice)
    {
        $user = Auth::user();

        if ($user->isCustomer() && $invoice->user_id !== $user->id) {
            return response('Unauthorized.', 401);
        }

        $invoice = $invoice->updateInvoice($request);

        GenerateInvoicePdfJob::dispatch($invoice, true);

        return response()->json([
            'invoice' => $invoice,
            'success' => true,
        ]);
    }

    /**
     * delete the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteInvoiceRequest $request)
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            return response('Unauthorized.', 401);
        }

        Invoice::destroy($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }
}