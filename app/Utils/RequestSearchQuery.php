<?php

namespace App\Utils;

use Illuminate\Http\Request;
use App\Exports\DataTableExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RequestSearchQuery
{
    /**
     * @var \Request
     */
    private $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    /**
     * Construct the Request and Builder instances.
     *
     * @param Request $request
     * @param Builder $query
     * @param array $searchables
     */
    public function __construct(Request $request, Builder $query, $searchables = [])
    {
        $this->request = $request;
        $this->query = $query;

        $this->initializeQuery($searchables);
    }

    /**
     * Get the localized column.
     *
     * @param Model $model
     * @param $column
     * @return string
     */
    private function getLocalizedColumn(Model $model, $column)
    {
        if (property_exists($model, 'translatable') && in_array($column, $model->translatable, true)) {
            $locale = app()->getLocale();

            return "$column->$locale";
        }

        return $column;
    }

    /**
     * Initialize the query for searching.
     *
     * @param array $searchables
     */
    public function initializeQuery($searchables = [])
    {
        $model = $this->query->getModel();

        if ($column = $this->request->get('column')) {
            $this->query->orderBy(
                $this->getLocalizedColumn($model, $column),
                $this->request->get('direction') ?? 'asc'
            );
        }

        if ($search = $this->request->get('search')) {
            $this->query->where(function (Builder $query) use ($model, $searchables, $search) {
                foreach ($searchables as $key => $searchableColumn) {
                    $query->orWhere(
                        $this->getLocalizedColumn($model, $searchableColumn), 'like', "%{$search}%"
                    );
                }
            });
        }
    }

    /**
     * Get the result per page.
     *
     * @param $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function result($columns)
    {
        return $this->query->paginate($this->request->get('perPage'), $columns);
    }

    /**
     * Export the data to the excel.
     *
     * @param       $columns
     * @param array $headings
     * @param       $fileName
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export($columns, $headings, $fileName)
    {
        $currentDate = date('dmY-His');

        return Excel::download(
            new DataTableExport($headings, $this->query, $columns),
            "$fileName-export-$currentDate.xlsx"
        );
    }
}
