<?php

namespace App\Unifin\Repositories\ClientReporting;

interface ReportInterface {
	public function generateReport($request);
}