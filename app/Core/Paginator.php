<?php

namespace App\Core;

class Paginator
{
    protected int $totalItems;
    protected int $itemsPerPage;
    protected int $currentPage;
    protected int $totalPages;
    protected string $baseUrl;

    public function __construct(int $totalItems, int $itemsPerPage = 15, int $currentPage = 1, string $baseUrl = '/')
    {
        $this->totalItems = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = max(1, $currentPage);
        $this->baseUrl = $baseUrl;
        
        $this->totalPages = (int) ceil($this->totalItems / $this->itemsPerPage);
        
        // Ensure current page is not greater than total pages
        $this->currentPage = min($this->currentPage, $this->totalPages);
    }

    /**
     * Get the items for the current page
     */
    public function items(array $allItems): array
    {
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        return array_slice($allItems, $offset, $this->itemsPerPage);
    }

    /**
     * Get the offset for database queries
     */
    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    /**
     * Get the limit for database queries
     */
    public function limit(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Check if there are more pages
     */
    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    /**
     * Check if there are previous pages
     */
    public function hasPreviousPages(): bool
    {
        return $this->currentPage > 1;
    }

    /**
     * Get the next page number
     */
    public function nextPage(): int
    {
        return min($this->currentPage + 1, $this->totalPages);
    }

    /**
     * Get the previous page number
     */
    public function previousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    /**
     * Get the total number of pages
     */
    public function totalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Get the current page number
     */
    public function currentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get the total number of items
     */
    public function totalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * Get the items per page
     */
    public function itemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Generate pagination links
     */
    public function links(int $pagesOnEachSide = 3): string
    {
        if ($this->totalPages <= 1) {
            return '';
        }

        $html = '<nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">';
        $html .= '<div class="flex flex-1 justify-between sm:hidden">';
        
        // Previous button for mobile
        if ($this->hasPreviousPages()) {
            $html .= '<a href="' . $this->pageUrl($this->previousPage()) . '" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>';
        }
        
        // Next button for mobile
        if ($this->hasMorePages()) {
            $html .= '<a href="' . $this->pageUrl($this->nextPage()) . '" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>';
        }
        
        $html .= '</div>';
        $html .= '<div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">';
        $html .= '<div>';
        $html .= '<p class="text-sm text-gray-700">';
        $html .= 'Showing <span class="font-medium">' . ($this->offset() + 1) . '</span> to <span class="font-medium">' . min($this->offset() + $this->itemsPerPage(), $this->totalItems()) . '</span> of <span class="font-medium">' . $this->totalItems() . '</span> results';
        $html .= '</p>';
        $html .= '</div>';
        $html .= '<div>';
        $html .= '<nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">';

        // Previous button
        if ($this->hasPreviousPages()) {
            $html .= '<a href="' . $this->pageUrl($this->previousPage()) . '" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">';
            $html .= '<span class="sr-only">Previous</span>';
            $html .= '<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>';
            $html .= '</a>';
        }

        // Page numbers
        $start = max(1, $this->currentPage() - $pagesOnEachSide);
        $end = min($this->totalPages(), $this->currentPage() + $pagesOnEachSide);

        // Show first page and ellipsis if needed
        if ($start > 1) {
            $html .= '<a href="' . $this->pageUrl(1) . '" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</a>';
            if ($start > 2) {
                $html .= '<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>';
            }
        }

        // Page numbers
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->currentPage()) {
                $html .= '<span class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">' . $i . '</span>';
            } else {
                $html .= '<a href="' . $this->pageUrl($i) . '" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">' . $i . '</a>';
            }
        }

        // Show last page and ellipsis if needed
        if ($end < $this->totalPages()) {
            if ($end < $this->totalPages() - 1) {
                $html .= '<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>';
            }
            $html .= '<a href="' . $this->pageUrl($this->totalPages()) . '" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">' . $this->totalPages() . '</a>';
        }

        // Next button
        if ($this->hasMorePages()) {
            $html .= '<a href="' . $this->pageUrl($this->nextPage()) . '" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">';
            $html .= '<span class="sr-only">Next</span>';
            $html .= '<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>';
            $html .= '</a>';
        }

        $html .= '</nav>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</nav>';

        return $html;
    }

    /**
     * Generate simple pagination links
     */
    public function simpleLinks(): string
    {
        if ($this->totalPages() <= 1) {
            return '';
        }

        $html = '<nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">';
        $html .= '<div class="flex flex-1 justify-between sm:hidden">';
        
        // Previous button for mobile
        if ($this->hasPreviousPages()) {
            $html .= '<a href="' . $this->pageUrl($this->previousPage()) . '" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>';
        } else {
            $html .= '<span class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500">Previous</span>';
        }
        
        // Next button for mobile
        if ($this->hasMorePages()) {
            $html .= '<a href="' . $this->pageUrl($this->nextPage()) . '" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>';
        } else {
            $html .= '<span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500">Next</span>';
        }
        
        $html .= '</div>';
        $html .= '<div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">';
        $html .= '<div>';
        $html .= '<p class="text-sm text-gray-700">';
        $html .= 'Showing <span class="font-medium">' . ($this->offset() + 1) . '</span> to <span class="font-medium">' . min($this->offset() + $this->itemsPerPage(), $this->totalItems()) . '</span> of <span class="font-medium">' . $this->totalItems() . '</span> results';
        $html .= '</p>';
        $html .= '</div>';
        $html .= '<div>';
        $html .= '<nav class="flex space-x-2" aria-label="Pagination">';

        // Previous button
        if ($this->hasPreviousPages()) {
            $html .= '<a href="' . $this->pageUrl($this->previousPage()) . '" class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Previous</a>';
        } else {
            $html .= '<span class="relative inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-500 ring-1 ring-inset ring-gray-300 focus-visible:outline-offset-0">Previous</span>';
        }

        // Next button
        if ($this->hasMorePages()) {
            $html .= '<a href="' . $this->pageUrl($this->nextPage()) . '" class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Next</a>';
        } else {
            $html .= '<span class="relative inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-500 ring-1 ring-inset ring-gray-300 focus-visible:outline-offset-0">Next</span>';
        }

        $html .= '</nav>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</nav>';

        return $html;
    }

    /**
     * Generate URL for a page
     */
    protected function pageUrl(int $page): string
    {
        if ($page <= 1) {
            // Remove page parameter for first page
            $url = preg_replace('/[?&]page=[^&]*/', '', $this->baseUrl);
            if (strpos($url, '?') === false && strpos($this->baseUrl, '?') !== false) {
                $url = str_replace('?', '', $url);
            }
            return $url;
        }
        
        // Add or update page parameter
        if (strpos($this->baseUrl, '?') === false) {
            return $this->baseUrl . '?page=' . $page;
        } elseif (strpos($this->baseUrl, 'page=') === false) {
            return $this->baseUrl . '&page=' . $page;
        } else {
            return preg_replace('/page=[^&]*/', 'page=' . $page, $this->baseUrl);
        }
    }
}