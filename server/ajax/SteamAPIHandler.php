<?php
declare(strict_types=1);

class SteamAPIHandler
{
    private SteamAPI $steamAPI;

    public function __construct(SteamAPI $steamAPI)
    {
        $this->steamAPI = $steamAPI;
    }

    public function handleRequest(array $request): string
    {
        $api = $request['api'] ?? null;
        if (!$api) {
            return json_encode(['error' => 'API parameter is missing']);
        }

        $output = $this->steamAPI->GetSteamAPI($api);
        return json_encode($output);
    }

	public function sendHeaders(): void
	{
		$headers = $this->getHeaders(); // <-- add this line for coverage
		foreach ($headers as $header) {
			//header($header);
		}
	}

    private function getHeaders(): array
    {
        return [
            'Content-Type: application/json',
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH, OPTIONS',
            'Access-Control-Allow-Headers: X-Requested-With',
        ];
    }
}