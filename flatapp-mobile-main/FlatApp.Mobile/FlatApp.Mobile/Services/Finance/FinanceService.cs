using FlatApp.Mobile.Models.Finance;
using FlatApp.Mobile.Services.Api;

namespace FlatApp.Mobile.Services.Finance;

public sealed class FinanceService(IApiClient apiClient) : IFinanceService
{
    public Task<FinanceResponse?> GetAsync(CancellationToken cancellationToken = default)
    {
        return apiClient.GetAsync<FinanceResponse>("finance", cancellationToken);
    }

    public async Task<FinanceExpense?> CreateExpenseAsync(CreateFinanceExpenseRequest request, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<CreateFinanceExpenseRequest, FinanceExpenseResponse>("finance/expenses", request, cancellationToken);

        return response?.Expense;
    }

    public async Task<FinanceExpense?> UpdateExpenseAsync(int id, CreateFinanceExpenseRequest request, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PutAsync<CreateFinanceExpenseRequest, FinanceExpenseResponse>($"finance/expenses/{id}", request, cancellationToken);

        return response?.Expense;
    }

    public Task DeleteExpenseAsync(int id, CancellationToken cancellationToken = default)
    {
        return apiClient.DeleteAsync($"finance/expenses/{id}", cancellationToken);
    }

    public async Task<FinanceSettlement?> CreateSettlementAsync(CreateFinanceSettlementRequest request, CancellationToken cancellationToken = default)
    {
        var response = await apiClient.PostAsync<CreateFinanceSettlementRequest, FinanceSettlementResponse>("finance/settlements", request, cancellationToken);

        return response?.Settlement;
    }

    public Task DeleteSettlementAsync(int id, CancellationToken cancellationToken = default)
    {
        return apiClient.DeleteAsync($"finance/settlements/{id}", cancellationToken);
    }
}
