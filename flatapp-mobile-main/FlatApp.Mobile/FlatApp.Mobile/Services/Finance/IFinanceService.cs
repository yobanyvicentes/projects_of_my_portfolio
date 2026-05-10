using FlatApp.Mobile.Models.Finance;

namespace FlatApp.Mobile.Services.Finance;

public interface IFinanceService
{
    Task<FinanceResponse?> GetAsync(CancellationToken cancellationToken = default);

    Task<FinanceExpense?> CreateExpenseAsync(CreateFinanceExpenseRequest request, CancellationToken cancellationToken = default);

    Task<FinanceExpense?> UpdateExpenseAsync(int id, CreateFinanceExpenseRequest request, CancellationToken cancellationToken = default);

    Task DeleteExpenseAsync(int id, CancellationToken cancellationToken = default);

    Task<FinanceSettlement?> CreateSettlementAsync(CreateFinanceSettlementRequest request, CancellationToken cancellationToken = default);

    Task DeleteSettlementAsync(int id, CancellationToken cancellationToken = default);
}
